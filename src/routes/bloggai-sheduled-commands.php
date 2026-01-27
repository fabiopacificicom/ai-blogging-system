<?php

use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Support\Facades\Schedule;
use PacificDev\BlogAi\Models\Post;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PacificDev\BlogAi\Services\OpenAi;

use PacificDev\BlogAi\Models\Setting;



if (\Schema::hasTable('settings')) {
  // Get timezone
  $timezone = Setting::get('scheduler_timezone', 'Europe/Rome');
  
  // Construct the CRON for post generation
  $postGenDays = Setting::get('post_generation_schedule_days', [2, 3]);
  $postShareDays = Setting::get('post_share_schedule_days', [3, 4]);
  
  $postsCron = constructCron($postGenDays, Setting::get('post_generation_schedule_time', '09:00'), $timezone);
  
  // Get multiple share times
  $postShareTimes = Setting::get('post_share_schedule_times', []);
  
  // Fallback to single time if no times array
  if (empty($postShareTimes)) {
    $postShareTimes = [Setting::get('post_share_schedule_time', '13:00')];
  }
  //dd($postsCron, $postShareTimes);

  // Only schedule post generation if days are configured
  if (!empty($postGenDays) && is_array($postGenDays) && array_filter($postGenDays)) {
    Schedule::command('bloggai:post')->cron($postsCron);
  }

  // Schedule multiple share jobs (one for each configured time)
  if (!empty($postShareDays) && is_array($postShareDays) && array_filter($postShareDays)) {
    foreach ($postShareTimes as $index => $shareTime) {
      if (empty($shareTime)) continue;
      
      $sharesCron = constructCron($postShareDays, $shareTime, $timezone);
      
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
        try {
            $token = decrypt($social->token);
        } catch (\Throwable $decryptEx) {
            // Token appears not encrypted or decryption failed; fall back to raw token for testing
            Log::warning('LinkedIn token decryption failed, falling back to raw token', ['social_id' => $social->id ?? null, 'error' => $decryptEx->getMessage()]);
            $token = $social->token;
        }
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
        $payload = [
            'social_id' => $social->id ?? null,
            'share_id' => $social->share_id ?? null,
            'user_id' => $social->user_id ?? null,
            'token_raw' => $social->token ?? null,
        ];

        Log::error('❌ LinkedIn share failed: ' . $e->getMessage(), ['payload' => $payload, 'exception' => $e]);
    }
    
      })->name('bloggai.share.' . $index)->cron($sharesCron);
    }
  }
}


/**
 * The settings array is a plain array with
 * @param $days
 * @param $time
 */
// Ensure helper available even if this routes file is included multiple times.
if (! function_exists('constructCron')) {
    function constructCron($days, $time, $userTimezone = 'UTC')
    {
        // Normalize days into a comma-separated list of day-of-week numbers (0=Sun .. 6=Sat)
        $selected = [];
        if (is_array($days)) {
            foreach ($days as $k => $v) {
                // Case: numeric indexed array with numeric values e.g. [2,3]
                if (is_int($k) && (is_int($v) || (is_string($v) && ctype_digit($v)))) {
                    $selected[] = (int) $v;
                    continue;
                }

                // Case: associative like ['1' => true] or ['mon' => true]
                if ((is_int($k) || ctype_digit((string) $k)) && ($v === true || $v === 1 || $v === '1')) {
                    $selected[] = (int) $k;
                    continue;
                }

                if (is_string($k) && $v === true) {
                    $map = ['sun' => 0, 'mon' => 1, 'tue' => 2, 'wed' => 3, 'thu' => 4, 'fri' => 5, 'sat' => 6];
                    $key = strtolower(substr($k, 0, 3));
                    if (isset($map[$key])) {
                        $selected[] = $map[$key];
                    }
                }

                // Case: value is numeric string in indexed arrays
                if (is_string($v) && ctype_digit($v)) {
                    $selected[] = (int) $v;
                }
            }
        }

        $selected = array_values(array_unique(array_filter($selected, fn ($x) => $x !== null && $x !== '')));
        $daysPart = empty($selected) ? '*' : implode(',', $selected);

        // Parse time safely (expects HH:MM)
        $hour = 9;
        $minute = 0;
        if (is_string($time) && strpos($time, ':') !== false) {
            [$h, $m] = array_pad(explode(':', $time, 2), 2, '0');
            $hour = is_numeric($h) ? (int) $h : $hour;
            $minute = is_numeric($m) ? (int) $m : $minute;
        } elseif (is_numeric($time)) {
            $hour = (int) $time;
            $minute = 0;
        }

        $hour = max(0, min(23, $hour));
        $minute = max(0, min(59, $minute));

        // Convert user timezone to UTC for scheduler
        if ($userTimezone !== 'UTC') {
            try {
                $userTime = \Carbon\Carbon::createFromTime($hour, $minute, 0, $userTimezone);
                $utcTime = $userTime->setTimezone('UTC');
                $hour = $utcTime->hour;
                $minute = $utcTime->minute;
            } catch (\Exception $e) {
                // Fallback to user time if timezone conversion fails
                \Log::warning("Timezone conversion failed for {$userTimezone}: " . $e->getMessage());
            }
        }

        // Construct the CRON (minute hour day month day-of-week)
        return "{$minute} {$hour} * * {$daysPart}";
    }
}
