<?php

namespace PacificDev\BlogAi;

use Illuminate\Support\ServiceProvider;


use PacificDev\BlogAi\Commands\AiCreateArticle;
use PacificDev\BlogAi\Commands\BloggaiSocialShare;
use PacificDev\BlogAi\Traits\Actionable;
use Illuminate\Support\Facades\Blade;
use PacificDev\BlogAi\View\Components\Blog\PanelComponent;
use PacificDev\BlogAi\Livewire\Blog\PostsWordsCounter;
use Livewire\Livewire;
use PacificDev\BlogAi\Livewire\Blog\CreatePostForm;
use PacificDev\BlogAi\Livewire\Blog\EditPostForm;
use PacificDev\BlogAi\Livewire\Blog\PostStatusToggler;
use PacificDev\BlogAi\Livewire\Blog\PostsToggler;
use PacificDev\BlogAi\Livewire\Blog\SearchPosts;
use PacificDev\BlogAi\Livewire\Blog\PostsCalendar;
use PacificDev\BlogAi\Livewire\Blog\PostsPage;
use PacificDev\BlogAi\Livewire\Blog\Settings;

class PacificDevServiceProvider extends ServiceProvider
{

  use Actionable;

  public function register()
  {
    $this->mergeConfigFrom(
      __DIR__ . '/config/bloggai.php',
      'bloggai'
    );
  }
  public function boot()
  {
    // load npm packages
    $this->loadPackages();


    // Loads required environment variables in the .env file
    $this->loadEnvironment();

    // Register the component
    Blade::component('pacificdev-panel', PanelComponent::class);
    Livewire::component('blog.posts-words-counter', PostsWordsCounter::class);
    Livewire::component('blog.posts-toggler', PostsToggler::class);
    Livewire::component('blog.post-status-toggler', PostStatusToggler::class);
    Livewire::component('blog.search-posts', SearchPosts::class);
    Livewire::component('blog.create', CreatePostForm::class);
    Livewire::component('blog.edit', EditPostForm::class);
    Livewire::component('blog.calendar', PostsCalendar::class);
    Livewire::component('blog.settings', Settings::class);
    Livewire::component('blog.posts', PostsPage::class);





    // Load views
    $this->loadViewsFrom(__DIR__ . '/resources/views', 'pacificdev');

    // load package assets and config file
    $this->publishPackageFiles();

    // load tests

    // load routes
    $this->loadRoutes(__DIR__ . '/routes');

    // load migrations
    $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

    // load controllers
    $this->loadControllersFrom(__DIR__ . '/Http/Controllers');


    // load middleware
    $this->loadMiddlewareFrom(__DIR__ . '/Http/Middleware');

    // Add a conditional check to ensure that the scheduler is loaded
    // only when the application is running in the console and 
    // the "schedule:run" command is being executed.
    if (
      $this->app->runningInConsole() &&
      in_array(\Request::server('argv', [])[1] ?? null, ['schedule:run', 'migrate'])
    ) {
      $this->app->booted(function () {
        $this->loadDefaultSheduler();
      });
    }

    // @deprecated Load Livewire
    // Livewire classes are autoloaded from the package, there is no need to 
    // copy the folder unless the user wants to override the package defaults.
    // in such case it can be useful to just publish it using vendor:publish
    //$this->loadLivewireComponentsFrom(__DIR__ . '/Livewire/Blog');


    // Add Commands to the app
    if ($this->app->runningInConsole()) {
      $this->commands([
        AiCreateArticle::class,
        BloggaiSocialShare::class,

      ]);
    }
  }
}
