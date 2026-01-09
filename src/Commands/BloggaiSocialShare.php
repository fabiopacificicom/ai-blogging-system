<?php

namespace PacificDev\BlogAi\Commands;

use PacificDev\BlogAi\Models\Social;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Services\LinkedInService;
use Symfony\Component\Console\Input\InputArgument;

class BloggaiSocialShare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bloggai:share';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Share the given blog post on a social network';

    public function configure()
    {
        $this->addArgument('social', InputArgument::OPTIONAL, 'Name of the social network', 'linkedin');
        $this->addArgument('text', InputArgument::OPTIONAL, 'Hi! Check out this link ');
        $this->addArgument('url', InputArgument::OPTIONAL, 'The link of the blog post to share on social networks', config('app.url'));
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // takes only the superadmin user_id - other users won't share on their account.
        // retrives the social connection given the social argument
        $social = Social::where('user_id', 1)->where('name', strtolower($this->argument('social')))->first();

        if (! $social) {
            $this->error('Token not found for user.');
            Log::error('There is likely an error for the user token');

            return Command::FAILURE;
        }

        $service = app(LinkedInService::class);
        $text = $this->argument('text') ?? '';
        $url = $this->argument('url') ?? config('app.url');

        $result = $service->share($social, $text, $url);

        if (! empty($result['ok'])) {
            return Command::SUCCESS;
        }

        $this->error('LinkedIn share failed: '.($result['reason'] ?? 'unknown'));
        Log::error('bloggai:share failed', ['result' => $result, 'social_id' => $social->id]);

        return Command::FAILURE;
    }
}
