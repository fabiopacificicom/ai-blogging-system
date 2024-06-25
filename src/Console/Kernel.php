<?php

namespace PacificDev\BlogAi\Console;

use PacificDev\BlogAi\Models\Post;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PacificDev\BlogAi\Services\OpenAi;
use PacificDev\BlogAi\Commands\BloggaiSocialShare;;

/**
 * This is for laravel 10
 */
class Kernel extends ConsoleKernel
{

  protected $commands = [BloggaiSocialShare::class];

  /**
   * Define the application's command schedule.
   *
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    // $schedule->command('inspire')->hourly();
    $schedule->command('bloggai:post')->weeklyOn([2, 3], '13:12');

    $schedule->call(function () {
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
    })->name('bloggai.share')->weeklyOn([3, 4], '09:00');
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
