<?php

namespace PacificDev\BlogAi\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use PacificDev\BlogAi\Commands\ScaffoldBlog;

class Installer extends Command
{

  use ScaffoldBlog;

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
    return $this->scaffoldBlog();
  }
}
