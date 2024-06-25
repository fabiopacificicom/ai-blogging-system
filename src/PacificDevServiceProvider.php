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
use PacificDev\BlogAi\Livewire\Blog\PostStatusToggler;
use PacificDev\BlogAi\Livewire\Blog\PostsToggler;
use PacificDev\BlogAi\Livewire\Blog\SearchPosts;

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


    $this->loadDefaultSheduler();

    // Loads required environment variables in the .env file
    $this->loadEnvironment();

    // Register the component
    Blade::component('pacificdev-panel', PanelComponent::class);
    Livewire::component('blog.posts-words-counter', PostsWordsCounter::class);
    Livewire::component('blog.posts-toggler', PostsToggler::class);
    Livewire::component('blog.post-status-toggler', PostStatusToggler::class);
    Livewire::component('blog.search-posts', SearchPosts::class);
    Livewire::component('blog.create', CreatePostForm::class);


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


    // Load Livewire
    $this->loadLivewireComponentsFrom(__DIR__ . '/Livewire/Blog');


    // Add Commands to the app
    if ($this->app->runningInConsole()) {
      $this->commands([
        AiCreateArticle::class,
        BloggaiSocialShare::class,

      ]);
    }
  }
}
