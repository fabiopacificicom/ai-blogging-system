<?php

use Illuminate\Support\Facades\Schedule;
use PacificDev\BlogAi\Models\Post;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PacificDev\BlogAi\Services\OpenAi;

use PacificDev\BlogAi\Models\Setting;


$postGenerationDays = Setting::get('post_generation_schedule_days', []);
$postGenerationTime = Setting::get('post_generation_schedule_time', '09:00');
$postShareDays = Setting::get('post_share_schedule_days', []);
$postShareTime = Setting::get('post_share_schedule_time', '13:00');


Schedule::command('bloggai:post')->weeklyOn($postGenerationDays, $postGenerationTime);

Schedule::call(function () {
  $latestPost = Post::where('status', 'public')->latest()->first();

  if (!$latestPost) {
    Log::info('Nothing to share');

    return;
  }

  $postUrl = URL::to('/posts/' . $latestPost?->slug);
  $postSummary = $latestPost?->summary;
  $openAi = new OpenAi();
  $laiResponse = $openAi->chat([
    'messages' => [
      config('bloggai.presets.system'), // the system message
      config('bloggai.presets.shareInstructions'), // the user instructions to generate a post given the below summary
      [
        'role' => 'user',
        'content' => $postSummary,
      ], // the latest post summary to generate the share text from
    ],
    'max_tokens' => config('bloggai.presets.share.max_tokens', 550),
    'temperature' => config('bloggai.presets.share.temperature', 0.2),
  ]);

  $shareText = $openAi->getAnswer($laiResponse);
  if (!$shareText) {
    Log::error($openAi->getFailureMessage($laiResponse));

    return;
  }

  /* TODO:
            the social name should be dynamic and not hardcoded when multiple social will be available
            */
  $this->call('bloggai:share', ['social' => 'linkedin', 'text' => $shareText, 'url' => $postUrl]);
})->name('bloggai.share')->weeklyOn($postShareDays, $postShareTime);
