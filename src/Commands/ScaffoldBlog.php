<?php

use Illuminate\Filesystem\Filesystem;

trait ScaffoldBlog
{
  protected function scaffoldBlog()
  {
    // ensure controllers folder exists
    (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
    // copy controllers from the package stubs
    (new Filesystem)->copyDirectory(__DIR__ . '../../stubs/app/Http/Controllers', app_path('Http/Controllers'));


    // ensure Requests folder exists
    (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
    // copy controllers from the package stubs
    (new Filesystem)->copyDirectory(__DIR__  .  '../../stubs/app/Http/Requests', app_path('Http/Requests'));


    // ensure middleware folder exists

    (new Filesystem)->ensureDirectoryExists(app_path('Http/Middleware'));
    // copy controllers from the package stubs
    (new Filesystem)->copyDirectory(__DIR__  .   '../../stubs/app/Http/Middleware', app_path('Http/Middleware'));
  }
}
