<?php

namespace PacificDev\BlogAi\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Log;

class ProcessImageOptimization implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 60;
    /**
     * Create a new job instance.
     */
    public function __construct(public string $path)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //dd($this->path);
        Log::info('starting optimization at: ' . $this->path);
        $optimizerChain = OptimizerChainFactory::create();
        $optimizerChain->optimize(public_path('storage/' . $this->path));
        Log::info('Optimization Complete at path: ' . $this->path);
    }
}
