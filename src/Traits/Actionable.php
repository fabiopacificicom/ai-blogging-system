<?php

namespace PacificDev\BlogAi\Traits;

use Illuminate\Support\Facades\File;

trait Actionable
{


  private function publishPackageFiles()
  {
    // publish views
    $this->publishes([
      __DIR__ . '/../resources/views' => resource_path('views/vendor/pacificdev/blog'),
    ]);


    // publish package assets for the admin and guest
    $this->publishes([
      __DIR__ . '/../resources/admin/js/admin.js' => resource_path('js/vendor/pacificdev/blog-ai/admin.js'),
      __DIR__ . '/../resources/admin/scss/admin.scss' => resource_path('scss/vendor/pacificdev/blog-ai/admin.scss'),
      __DIR__ . '/../resources/admin/css/toastui-editor-dark.css' => resource_path('css/vendor/pacificdev/blog-ai/toastui-editor-dark.css'),
      __DIR__ . '/../resources/admin/css/toastui-editor.css' => resource_path('css/vendor/pacificdev/blog-ai/toastui-editor.css'),

      __DIR__ . '/../resources/guest/js/app.js' => resource_path('js/vendor/pacificdev/blog-ai/app.js'),
      __DIR__ . '/../resources/guest/scss/app.scss' => resource_path('scss/vendor/pacificdev/blog-ai/app.scss'),

      __DIR__ . '/../resources/common/js/prism.js' => public_path('vendor/pacificdev/blog-ai/js/prism.js'),
      __DIR__ . '/../resources/common/css/prism.css' => public_path('vendor/pacificdev/blog-ai/css/prism.css'),

    ], 'blog-ai-assets');



    // Load config
    $this->publishes([
      __DIR__ . '/../config/bloggai.php' => config_path('bloggai.php')
    ]);
  }

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


  private function loadRoutes($path)
  {
    // If the route file does not exist we copy it and append it to the end of web.php
    // we also add the constants required for the social connections to the .env file
    if (!File::exists(base_path('/routes/bloggai.php'))) {
      File::copy($path . '/bloggai-routes.php', base_path('/routes/bloggai.php'));
      $web_php_file = 'routes/web.php';
      $this->append_to_file($web_php_file, "require __DIR__ . '/bloggai.php';");

      // Append the LINKEDIN_ constants to the .env file
      $env_file_path = '.env';
      $this->append_to_file($env_file_path, 'LINKEDIN_CLIENT_ID=your_client_id_key_goes_here');
      $this->append_to_file($env_file_path, 'LINKEDIN_CLIENT_SECRET=your_secrets_here');
    }
  }

  private function loadModelsFrom($path)
  {
    File::copyDirectory(
      $path,
      base_path('/app/Models')
    );
  }

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
