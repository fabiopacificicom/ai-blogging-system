<?php

use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Support\Facades\Schedule;
use PacificDev\BlogAi\Models\Post;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PacificDev\BlogAi\Services\OpenAi;

use PacificDev\BlogAi\Models\Setting;



if (\Schema::hasTable('settings')) {
  //$schedule =  app()->make(Schedule::class);
  //dd($schedule);

  // Construct the CRON
  $postsCron = constructCron(Setting::get('post_generation_schedule_days', [2, 3]), Setting::get('post_generation_schedule_time', '09:00'));
  $sharesCron = constructCron(Setting::get('post_share_schedule_days', [3, 4]), Setting::get('post_share_schedule_time', '13:00'));
  //dd($postsCron, $sharesCron);


  Schedule::command('bloggai:post')->cron($postsCron);

  Schedule::call(function () {
    // Resolve configured shareable models mapping from config.
    // Expected format: ['post' => \PacificDev\BlogAi\Models\Post::class, 'course' => \App\Models\Course::class]
    $mapping = config('linkedin.share_models', ['post' => \PacificDev\BlogAi\Models\Post::class]);
    if (! is_array($mapping) || empty($mapping)) {
        $mapping = ['post' => \PacificDev\BlogAi\Models\Post::class];
    }

    // Pick one alias at random from the configured keys
    $aliases = array_keys($mapping);
    $alias = $aliases[array_rand($aliases)];
    $modelClass = $mapping[$alias] ?? null;

    if (! $modelClass || ! class_exists($modelClass)) {
        Log::warning('Configured LinkedIn share model not found: '.$alias);
        return;
    }

    // Use LinkedInShare helper to get eligible content by alias
    $eligible = \App\Models\LinkedInShare::getEligibleContent($alias, 1);
    if ($eligible->isEmpty()) {
        Log::info("📅 No eligible {$alias}s to share on LinkedIn (alias: {$alias})");
        // Try other configured models as fallback
        foreach ($mapping as $fallbackAlias => $fallbackClass) {
            if ($fallbackAlias === $alias) continue;
            $fallbackEligible = \App\Models\LinkedInShare::getEligibleContent($fallbackAlias, 1);
            if (! $fallbackEligible->isEmpty()) {
                $eligible = $fallbackEligible;
                $alias = $fallbackAlias;
                $modelClass = $fallbackClass;
                break;
            }
        }
    }

    if (empty($eligible) || $eligible->isEmpty()) {
        Log::info('📅 No eligible content to share on LinkedIn after checking configured models');
        return;
    }

    $content = $eligible->first();
    $type = $alias;

    // Generate share URL (assumes conventional plural route naming)
    $shareUrl = URL::to('/' . $type . 's/' . ($content->slug ?? $content->id));

    // Generate AI promotional text (generic flow for any configured model)
    $openAi = new OpenAi();

    // Build title/summary from available fields on the model
    $title = $content->title ?? $content->name ?? '';
    $summary = $content->summary ?? $content->description ?? (
        isset($content->content) ? strip_tags(substr($content->content, 0, 300)) : ''
    );

    $template = config('linkedin.promotional_post_template');
    if ($template && trim($template) !== '') {
        $prompt = str_replace(
            ['{title}', '{description}', '{summary}'],
            [$title, $summary, $summary],
            $template
        );

        $aiResponse = $openAi->chat([
            'messages' => [
                config('bloggai.presets.system'),
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'max_tokens' => config('bloggai.presets.share.max_tokens', 550),
            'temperature' => config('bloggai.presets.share.temperature', 0.2),
        ]);
    } else {
        // Fallback to the tested share instructions used for posts
        $aiResponse = $openAi->chat([
            'messages' => [
                config('bloggai.presets.system'),
                config('bloggai.presets.shareInstructions'),
                [
                    'role' => 'user',
                    'content' => $summary ?: $title,
                ],
            ],
            'max_tokens' => config('bloggai.presets.share.max_tokens', 550),
            'temperature' => config('bloggai.presets.share.temperature', 0.2),
        ]);
    }

    $shareText = $openAi->getAnswer($aiResponse) ?: $title;
    
    if (!$shareText) {
        Log::error('❌ AI failed to generate share text for ' . $type . ' #' . $content->id);
        return;
    }
    
    // Get LinkedIn connection
    $social = \PacificDev\BlogAi\Models\Social::where('name', 'linkedin')->first();
    
    if (!$social) {
        Log::error('❌ No LinkedIn connection found for scheduled share');
        return;
    }
    
    // Share to LinkedIn
    try {
        $token = decrypt($social->token);
        \PacificDev\BlogAi\Models\Social::shareOnLinkedin(
            $social->share_id,
            $token,
            $shareText,
            $shareUrl
        );
        
        // Record the share using the resolved model class for the alias
        \App\Models\LinkedInShare::create([
            'shareable_type' => $modelClass,
            'shareable_id' => $content->id,
            'user_id' => $social->user_id,
            'share_text' => $shareText,
            'share_url' => $shareUrl,
            'shared_at' => now(),
            'source' => 'scheduler',
        ]);
        
        Log::info("✅ LinkedIn share successful: " . ucfirst($type) . " #{$content->id} - {$content->title}");
        
    } catch (\Throwable $e) {
        Log::error('❌ LinkedIn share failed: ' . $e->getMessage());
    }
    
  })->name('bloggai.share')->cron($sharesCron);
}


/**
 * The settings array is a plain array with
 * @param $days
 * @param $time
 */
function constructCron($days, $time)
{

  // Convert the associative array to a simple string of day numbers
  $days = implode(',', array_keys(array_filter($days, function ($value) {
    return $value === true;
  })));

  // extract times and hours
  [$hour, $minute] = explode(':', $time);


  // Construct the CRON
  return "{$minute} {$hour} * * {$days}";
}
