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

  private function loadScheduler()
  {
    $schedule =  $this->app->make(Schedule::class);
    $postGenerationDays = Setting::get('post_generation_schedule_days', []);
    $postGenerationTime = Setting::get('post_generation_schedule_time', '09:00');
    $postShareDays = Setting::get('post_share_schedule_days', []);
    $postShareTime = Setting::get('post_share_schedule_time', '13:00');


    // $schedule->command('inspire')->hourly();
    $schedule->command('bloggai:post')->weeklyOn($postGenerationDays, $postGenerationTime);

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
    })->name('bloggai.share')->weeklyOn($postShareDays, $postShareTime);
  }

  private function loadPackages()
  {


    $packageJson = json_decode(file_get_contents(base_path('package.json')), true);

    //dd($packageJson, array_key_exists('ldrs', $packageJson['dependencies'])); //array, false

    // set loaders package
    if (!array_key_exists('ldrs', $packageJson['dependencies'])) {
      $packageJson['dependencies']['ldrs'] = '^1.0.1';
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
      __DIR__ . '/../config/bloggai.php' => config_path('bloggai.php')
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


  /* 
  TODO: remove deprecated
  @deprecated - private function loadDefaultSheduler()
  {

    // get the framework version
    if (
      File::exists(base_path('/routes/bloggai-sheduled-commands.php')) ||
      File::exists(base_path('/app/Console/Kernel-bloggai.php'))
    ) {
      return;
    }

    $version = intval(substr(App::version(), 0, 2));
    if ($version >= 11) {
      // copy the bloggai-sheduled-commands routes file 
      File::copy(__DIR__ . '/../routes/bloggai-sheduled-commands.php', base_path('/routes/bloggai-sheduled-commands.php'));
      $console_routes_php_file = 'routes/console.php';
      $this->append_to_file($console_routes_php_file, "require __DIR__ . '/bloggai-sheduled-commands.php';");
    } else {
      // copy the Console/Kernel.php file
      File::copy(__DIR__ . '/../Console/Kernel.php', base_path('/app/Console/Kernel-bloggai.php'));
    }
  } */


  private function loadEnvironment()
  {
    // Append the LINKEDIN_ constants to the .env file
    if (!env('LINKEDIN_CLIENT_ID') || !env('LINKEDIN_CLIENT_SECRET')) {
      $env_file_path = '.env';
      $this->append_to_file($env_file_path, 'LINKEDIN_CLIENT_ID=your_client_id_key_goes_here' . "\n");
      $this->append_to_file($env_file_path, 'LINKEDIN_CLIENT_SECRET=your_secrets_here');
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



  private function append_to_file($file, string $contents)
  {
    $file_path = base_path($file);
    $file_contents = file_get_contents($file_path);
    $file_contents .= $contents;
    file_put_contents($file_path, $file_contents);
  }
}
