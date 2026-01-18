<?php

namespace PacificDev\BlogAi\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Console\Scheduling\Schedule;
use PacificDev\BlogAi\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PacificDev\BlogAi\Services\OpenAi;
use PacificDev\BlogAi\Models\Setting;

trait Actionable
{



  private function loadPackages()
  {


    $packageJson = json_decode(file_get_contents(base_path('package.json')), true);

    //dd($packageJson, array_key_exists('ldrs', $packageJson['dependencies'])); //array, false

    // set loaders package
    if (!array_key_exists('ldrs', $packageJson['dependencies'])) {
      $packageJson['dependencies']['ldrs'] = '^1.0.1';
      file_put_contents(base_path('package.json'), json_encode($packageJson));
    }
    if (!array_key_exists('bootstrap', $packageJson['dependencies'])) {
      $packageJson['dependencies']['bootstrap'] = '^5.3.3';
      file_put_contents(base_path('package.json'), json_encode($packageJson));
    }
  }

  private function publishPackageFiles()
  {

    $this->publishes([
      __DIR__ . '/../View/Components' => base_path('app/View/Components')
    ], 'pacificdev:ai-blog-components');

    $this->publishes([
      __DIR__ . '/../Livewire/Blog' => base_path('app/Livewire/Blog')
    ], 'pacificdev:ai-blog-livewire-classes');

    // publish package assets for the admin and guest
    $this->publishes([
      // Admin assets 
      __DIR__ . '/../resources/admin/js/admin.js' => resource_path('js/vendor/pacificdev/blog-ai/admin.js'),
      __DIR__ . '/../resources/admin/scss/admin.scss' => resource_path('scss/vendor/pacificdev/blog-ai/admin.scss'),

      // Guests assets
      __DIR__ . '/../resources/guest/js/app.js' => resource_path('js/vendor/pacificdev/blog-ai/app.js'),
      __DIR__ . '/../resources/guest/scss/app.scss' => resource_path('scss/vendor/pacificdev/blog-ai/app.scss'),

      // Common assets
      __DIR__ . '/../resources/common/js/prism.js' => public_path('js/vendor/pacificdev/blog-ai/prism.js'),
      __DIR__ . '/../resources/common/css/prism.css' => public_path('css/vendor/pacificdev/blog-ai/prism.css'),

      // Images
      __DIR__ . '/../resources/images/logo.png' => public_path('images/vendor/pacificdev/blog-ai/logo.png')



    ], 'pacificdev:ai-blog-assets');

    $this->publishes([
      __DIR__ . '/../resources/views' => resource_path('views/vendor/pacificdev/'),

    ], 'pacificdev:ai-blog-views');

    // Load config
    $this->publishes([
      __DIR__ . '/../config/bloggai.php' => config_path('bloggai.php'),
      __DIR__ . '/../config/linkedin.php' => config_path('linkedin.php')
    ], 'pacificdev:ai-blog-config');
  }

  // @deprecated Load Livewire
  /*  private function loadLivewireComponentsFrom($path)
  {
    // Verify that the destination directory exists or create it
    $destinationPath = base_path('app/Livewire/Blog');
    if (File::isDirectory($destinationPath)) {
      return;
    }
    File::makeDirectory($destinationPath, 0755, true);
    $success = File::copyDirectory($path, $destinationPath);

    // Handle the success or failure of the copy operation
    if (!$success) {
      // Handle the error appropriately
      throw new \Exception("Failed to copy from {$path} to {$destinationPath}");
    }
  } */



  private function loadDefaultSheduler()
  {

    if (
      File::exists(base_path('/routes/bloggai-sheduled-commands.php'))
    ) {
      return;
    }

    // get the framework version
    $version = intval(substr(App::version(), 0, 2));

    // Laravel 11 uses the routes/console.php file to define scheduled commands
    if ($version >= 11) {
      // copy the bloggai-sheduled-commands routes file 
      File::copy(__DIR__ . '/../routes/bloggai-sheduled-commands.php', base_path('/routes/bloggai-sheduled-commands.php'));
      $console_routes_php_file = 'routes/console.php';
      $this->append_to_file($console_routes_php_file, "require __DIR__ . '/bloggai-sheduled-commands.php';");
    } else {
      // Prior to laravel 11 the scheduler has to be loaded in the package service provider boot method
      // load the scheduler in the service proider boot method
      $this->loadScheduler();
    }
  }


  private function loadScheduler()
  {

    if (\Schema::hasTable('settings')) {
      $schedule =  $this->app->make(Schedule::class);


      // Construct the CRON
      $postsCron = $this->constructCron(Setting::get('post_generation_schedule_days', [2, 3]), Setting::get('post_generation_schedule_time', '09:00'));
      $sharesCron = $this->constructCron(Setting::get('post_share_schedule_days', [3, 4]), Setting::get('post_share_schedule_time', '13:00'));
      //dd($postsCron, $sharesCron);

      $schedule->command('bloggai:post')->cron($postsCron);

      $schedule->call(function () use ($schedule) {
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
        $schedule->call('bloggai:share', ['social' => 'linkedin', 'text' => $shareText, 'url' => $postUrl]);
      })->name('bloggai.share')->cron($sharesCron);
    }
  }


  /**
   * The settings array is a plain array with 
   * @param $days
   * @param $time
   */
  private function constructCron($days, $time)
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

    $selected = array_values(array_unique(array_filter($selected, fn($x) => $x !== null && $x !== '')));
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

    // Construct the CRON (minute hour day month day-of-week)
    return "{$minute} {$hour} * * {$daysPart}";
  }



  private function loadEnvironment()
  {
    // Append the LINKEDIN_ constants to the .env file only when not present
    $env_file_path = '.env';
    $env_full_path = base_path($env_file_path);

    if (!file_exists($env_full_path)) {
      return;
    }

    $env_contents = file_get_contents($env_full_path);

    if (!str_contains($env_contents, 'LINKEDIN_CLIENT_ID') && !str_contains($env_contents, 'LINKEDIN_CLIENT_SECRET')) {
      $contents = PHP_EOL . 'LINKEDIN_CLIENT_ID=your_client_id_key_goes_here' . PHP_EOL;
      $contents .= 'LINKEDIN_CLIENT_SECRET=your_secrets_here' . PHP_EOL;
      $this->append_to_file($env_file_path, $contents);
    }
  }



  private function loadRoutes($path)
  {
    // If the route file does not exist we copy it and append it to the end of web.php
    // we also add the constants required for the social connections to the .env file
    if (!File::exists(base_path('/routes/bloggai.php'))) {
      File::copy($path . '/bloggai-routes.php', base_path('/routes/bloggai.php'));
      $web_php_file = 'routes/web.php';
      $this->append_to_file($web_php_file, "require __DIR__ . '/bloggai.php';");
    }
  }

  /*
  TODO: remove deprecated
  @deprecated private function loadModelsFrom($path)
  {
    File::copyDirectory(
      $path,
      base_path('/app/Models')
    );
  } */

  private function loadControllersFrom($path)
  {

    if (is_null($path)) throw new Exception("Path cannot be null");


    if (!File::isDirectory(base_path('app/Http/Controllers/Blog'))) {
      // the Blog directory does not exists
      //dd('no blog folder');
      // copy the it from the package
      $this->info('need to create the Blog folder from path: ' . $path);
      File::copyDirectory($path, base_path('/app/Http/Controllers'));
    }
    // dd($path,  File::isDirectory(base_path('app/Http/Controllers/Blog/Admin')));
    else {

      // if the Admin and Guests folders exists
      // copy the files only otherwise we should copy the folders
      if (File::isDirectory(base_path('app/Http/Controllers/Blog/Admin'))) {
        File::copy($path . '/Blog/Admin/PostController.php', base_path('app/Http/Controllers/Blog/Admin/PostController.php'));
        File::copy($path . '/Blog/Admin/SocialController.php', base_path('app/Http/Controllers/Blog/Admin/SocialController.php'));
      }

      if (File::isDirectory(base_path('app/Http/Controllers/Blog/Guest'))) {
        File::copy($path . '/Blog/Guest/PostController.php', base_path('app/Http/Controllers/Blog/Guest/PostController.php'));
      }
    }
  }


  private function loadMiddlewareFrom($path)
  {

    if (is_null($path)) throw new Exception("Path cannot be null");


    if (!File::isDirectory(base_path('app/Http/Middleware/Blog'))) {
      File::copyDirectory($path, base_path('/app/Http/Middleware'));
    }
  }

  /**
   * Register database migration paths.
   *
   * @param  array|string  $paths
   * @return void
   */
  protected function loadMigrationsFrom($paths)
  {
    $this->callAfterResolving('migrator', function ($migrator) use ($paths) {
      foreach ((array) $paths as $path) {
        $migrator->path($path);
      }
    });
  }

  private function append_to_file($file, string $contents)
  {
    $file_path = base_path($file);

    // Ensure file exists
    if (!file_exists($file_path)) {
      file_put_contents($file_path, $contents);
      return;
    }

    $file_contents = file_get_contents($file_path);

    // Ensure the file ends with a newline before appending
    if (substr($file_contents, -1) !== "\n") {
      $file_contents .= "\n";
    }

    $file_contents .= $contents;
    file_put_contents($file_path, $file_contents);
  }
}
