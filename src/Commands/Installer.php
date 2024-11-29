<?php

namespace PacificDev\BlogAi\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use PacificDev\BlogAi\Commands\ScaffoldBlog;
use PacificDev\BlogAi\Traits\Actionable;

class Installer extends Command
{

  use ScaffoldBlog, Actionable;

  /** 
   * Signature
   * @var string
   */

  protected $signature = 'blogai:install';

  protected $description = "Install the blog ai preset";

  /**
   * Run the command logic here
   */
  public function handle()
  {
    $this->info('Installing BlogAi');
    $this->scaffoldBlog();
    $this->info('Complete');
  }
}
