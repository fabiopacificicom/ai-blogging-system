<?php

namespace PacificDev\BlogAi\Commands;

use PacificDev\BlogAi\Traits\Actionable;

trait ScaffoldBlog
{
  use Actionable;
  protected function scaffoldBlog()
  {

    $this->loadControllersFrom(__DIR__ . '/../../stubs/app/Http/Controllers');
    $this->loadMiddlewareFrom(__DIR__ . '/../../stubs/app/Http/Middleware');
  }
}
