<?php

namespace PacificDev\BlogAi\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

trait Actionable
{


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
      __DIR__ . '/../resources/admin/js/postgen.js' => resource_path('js/vendor/pacificdev/blog-ai/postgen.js'),
      __DIR__ . '/../resources/admin/scss/admin.scss' => resource_path('scss/vendor/pacificdev/blog-ai/admin.scss'),
      __DIR__ . '/../resources/admin/css/toastui-editor-dark.css' => resource_path('css/vendor/pacificdev/blog-ai/toastui-editor-dark.css'),
      __DIR__ . '/../resources/admin/css/toastui-editor.css' => resource_path('css/vendor/pacificdev/blog-ai/toastui-editor.css'),

      // Guests assets
      __DIR__ . '/../resources/guest/js/app.js' => resource_path('js/vendor/pacificdev/blog-ai/app.js'),
      __DIR__ . '/../resources/guest/scss/app.scss' => resource_path('scss/vendor/pacificdev/blog-ai/app.scss'),

      // Common assets
      __DIR__ . '/../resources/common/js/prism.js' => public_path('js/vendor/pacificdev/blog-ai/prism.js'),
      __DIR__ . '/../resources/common/css/prism.css' => public_path('css/vendor/pacificdev/blog-ai/prism.css'),

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
  private function loadLivewireComponentsFrom($path)
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
  }


  private function loadDefaultSheduler()
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
  }


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

  /* private function loadModelsFrom($path)
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
