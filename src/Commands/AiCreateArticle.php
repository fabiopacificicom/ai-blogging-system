<?php

namespace PacificDev\BlogAi\Commands;

use PacificDev\BlogAi\Models\Topic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PacificDev\BlogAi\Services\OpenAi;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Arr;
use PacificDev\BlogAi\Models\Post;

class AiCreateArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bloggai:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new blog post with bloggai.net and AI ';

    protected function configure()
    {
        // Tile
        $this->addArgument('title-temperature', InputArgument::OPTIONAL, 'The temperature for the title', config('bloggai.presets.blog.title.temperature'));
        $this->addArgument('title-tokens', InputArgument::OPTIONAL, 'The Max number of tokens to generate for the title', config('bloggai.presets.blog.title.max_tokens'));
        $this->addArgument('target-audience', InputArgument::OPTIONAL, 'The Instructions to generate the title', config('bloggai.presets.blog.title.target_audience'));
        $this->addArgument('title-prompt', InputArgument::OPTIONAL, 'The Prompt to generate the title', config('bloggai.presets.blog.title.prompt'));

        // Image
        $this->addArgument('image-prompt', InputArgument::OPTIONAL, 'The Prompt to generate the image', config('bloggai.presets.blog.image.prompt'));
        // Summary
        $this->addArgument('summary-temperature', InputArgument::OPTIONAL, 'The temperature for the summary', config('bloggai.presets.blog.summary.temperature'));
        $this->addArgument('summary-tokens', InputArgument::OPTIONAL, 'The Max number of tokens to generate for the summary', config('bloggai.presets.blog.summary.max_tokens'));
        $this->addArgument('summary-prompt', InputArgument::OPTIONAL, 'The Prompt to generate the summary', config('bloggai.presets.blog.summary.prompt'));

        // Chat
        $this->addArgument('content-prompt', InputArgument::OPTIONAL, 'The Prompt to generate the post content', config('bloggai.presets.blog.content.prompt'));
        $this->addArgument('content-temperature', InputArgument::OPTIONAL, 'The temperature for the summary', config('bloggai.presets.blog.content.temperature'));
        $this->addArgument('content-tokens', InputArgument::OPTIONAL, 'The Max number of tokens to generate for the content', config('bloggai.presets.blog.content.max_tokens'));
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(OpenAi $ai)
    {
        $starTime = now();
        // GENERATE THE POST TITLE
        /* We generate the post title, but if its output is null, we shoud not continue */
        $titleResponse = $this->generateTitle($ai);
        //dd($titleResponse);

        if (!$titleResponse) {
            Log::info('❌ Cannot create the post, title failed to generate.');
            Log::error("Title: $titleResponse");
            exit;
        }
        $this->info('✅ Post title generated successfully');

        // if we have a title we can generate a slug
        // TODO: we need also to check if a post with the same slug already exists, if
        // so, we can count how many posts we have an append a counter at the end of the post title and
        // slug to avoid db violation.
        $title = json_decode($titleResponse, true);
        $slug = Str::slug($title['title']);
        Log::info('Post title: ' . $title['title']);

        //$post = Post::create(['title' => $title['title'], 'slug' => $slug]);
        $this->info('✅ Post title and slug saved');

        //dd($titleResponse, $title, $slug);

        // GENERATE THE POST CONTENT
        $this->info('⌛ Generating the post content...');
        $content = $this->generateContent($ai, $title['title']);
        //dd($content);
        if (!$content) {
            $message = '❌ Cannot create the post, content failed to generate.';
            $this->error($message);
            Log::info($message);
            Log::error("Content: $content");
            exit;
        }

        $this->info('✅ Post content generated successfully!');
        $this->info($content);

        // update the post by saving its content
        //$post->update(['content' => $content]);
        $this->info('✅ Post content saved');

        //dd($titleResponse, $title, $slug, $content, $content);

        // GENERATE THE POST SUMMARY
        /* Given the post content was successfully generated we now generate the post summary*/
        $this->info('⌛ Generating the post summary...');

        //dd($content);
        $summaryResponse = $this->generateSummary($ai, $content);

        //dd($summaryResponse);
        if (!$summaryResponse) {
            $message = '❌ Cannot create the post, content failed to generate.';
            $this->error($message);
            Log::info($message);
            Log::error("Summary: $summaryResponse");
            exit;
        }
        $this->info('✅ Post summary generated successfully!');
        $summary = json_decode($summaryResponse, true);
        //dd($summaryResponse, $summary);

        // update the post summary
        if (is_array($summary) && in_array('summary', $summary)) {
            $this->info($summary['summary']);

            $summary = $summary['summary'];
            $this->info('✅ Post summary saved');
        }



        // GENERATE THE POST IMAGE
        $this->info('⌛ Generating the post image...');

        $cover_image = $this->generateAndStoreImage($ai);
        $this->info('✅ Post cover image generated successfully!');

        $this->info('⌛ Updating the post ...');

        //update the image
        //$post->update(['cover_image' => $cover_image]);
        $this->info('✅ Post image saved');

        //dd($title, $slug, $summary, $content, $cover_image);
        $title = $title['title'];
        $summary = $summary['summary'];
        /* Log::info('this is the post data: ', [
            'title' => $title,
            'slug' => $slug,
            'summary' => $summary,
            'content' => $content,
            'cover_image' => $cover_image,

        ]);
         */
        //dd('hi');
        //TODO: remove, this is replaced by updates during the above process
        Post::create(compact('content', 'cover_image', 'title', 'summary', 'slug'));

        $doneTime = now()->diffForHumans($starTime);
        $message = "✅ Command successful! Article Generated in $doneTime";
        Log::info($message);

        return $this->info($message);
    }

    /**
     */
    private function generateTitle($ai)
    {
        /* TODO:
        The user should be able to chose if wants to pick topics randomply or follow them in order.-bottom-3
        We should generate a topics model and migration with a seeder. Then seed the db with the list in the config
        Then the user should be able to update them as needed (CRUD)
        So here, we should check if there are topics in the db, if not, we should load them from the config file.
        When we reach this point we should:
        - check if the user wants to follow topics in random order or not
        - query the db for the next topic to use.
        - save it to the variable below actually called $randomTopic.

        We should also consider that the post about this topic might be previously written.
        So, we should have a relationship between topics and posts, and check if we already written an article about the
        same topic:
        - IF (there are articles associated with the selected topic) we should pass each post summary to chat before generating the new post to content duplication.
        - Otherwise there are no previous posts about this topic, and we can safely move on.


        [FOR now keep the topic random]

        ideally: $bloggai->getTopic()
        */
        $randomTopic = Topic::where('active', 1)->count() > 0 ? Arr::random(Topic::where('active', 1)->get('name')->toArray())['name'] : config('bloggai.presets.blog.topics')[array_rand(config('bloggai.presets.blog.topics'))];
        //dd("Topic: $randomTopic - ".$this->argument('target-audience').$this->argument('title-prompt'));
        //dd($randomTopic, Arr::random(Topic::where('active', 1)->get()->toArray()));
        //dd($randomTopic, Topic::where('active')->count() > 0);




        $response = $ai->chat(
            [
                /* Overriding model here to use a specific one as json outputs is only available there */
                'model' => 'gpt-4-turbo-preview',
                'response_format' => ['type' => 'json_object'],
                'temperature' => $this->argument('title-temperature'),
                'max_tokens' => $this->argument('title-tokens'),
                'messages' => [
                    config('bloggai.presets.system'),
                    [
                        'role' => 'user',
                        'content' => $this->argument('title-prompt') . '[audience]' . $this->argument('target-audience') . '[topic]' . $randomTopic,
                    ],
                ],
            ]
        );
        //dd($response);

        return $ai->getAnswer($response);
    }

    /**
     */
    private function generateSummary($ai, $content)
    {
        $response = $ai->chat(
            [
                /* Overriding model here to use a specific one as json outputs is only available there */
                'model' => 'gpt-4-turbo-preview',
                'response_format' => ['type' => 'json_object'],
                'temperature' => $this->argument('summary-temperature'),
                'messages' => [
                    config('bloggai.presets.system'),
                    [
                        'role' => 'user',
                        'content' => $this->argument('summary-prompt') . '[blog-post]' .
                            $content . '[blog-post]',
                    ],
                ],
                'max_tokens' => $this->argument('summary-tokens'),
            ]
        );

        return $ai->getAnswer($response);
    }

    /**
     */
    private function generateContent($ai, $title)
    {
        $response = $ai->chat(
            [

                'temperature' => $this->argument('content-temperature'),
                'messages' => [
                    config('bloggai.presets.system'),
                    [
                        'role' => 'user',
                        'content' => $this->argument('content-prompt') . '[post-title]' .
                            $title . '[post-title]',
                    ],
                ],
                'max_tokens' => $this->argument('content-tokens'),
            ]
        );

        return $ai->getAnswer($response);
    }

    private function generateAndStoreImage($ai)
    {
        $imagePrompt = $this->argument('image-prompt');
        $cover_image_stream = $ai->generateImages($imagePrompt);
        //dd($cover_image_stream);
        $cover_image = '/images/' . uniqid('aimg_') . '.jpeg';
        Storage::put($cover_image, $cover_image_stream);

        return $cover_image;
    }
}
