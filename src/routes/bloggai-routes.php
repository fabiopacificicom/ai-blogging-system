<?php

use App\Http\Controllers\Blog\Admin\PostController;
use App\Http\Controllers\Blog\Admin\SocialController;
use App\Http\Controllers\Blog\Guest\PostController as BloggaiGuestPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use PacificDev\BlogAi\Livewire\Blog\EditPostForm;
use PacificDev\BlogAi\Services\OpenAi;
use PacificDev\BlogAi\Livewire\Blog\PostsPage as PostsPage;
use PacificDev\BlogAi\Livewire\Blog\Settings;
/* Add the message in the messages table */

Route::resource('posts', BloggaiGuestPostController::class)->parameters([
    'posts' => 'post:slug',
])->only(['index', 'show']);

Route::middleware(['auth', 'verified', 'superadmin'])->prefix('blog-ai')->name('admin.')->group(function () {


    Route::redirect('/', '/blog-ai/settings', 301);
    Route::get('posts', PostsPage::class)->name('posts.index');
    Route::get('posts/{post:slug}/edit', EditPostForm::class)->name('posts.edit');
    Route::resource('posts', PostController::class)->except(['show', 'index', 'edit'])->parameters([
        'posts' => 'post:slug',
    ]);

    Route::get('settings', Settings::class)->name('blog.settings');
    /*     Route::post('ai/blog', function (Request $request, OpenAi $ai) {
        // Requests that reach this endpoint will either want to generate a
        // - post title
        // - post summary
        // - post content

        // When we receive a request we need to check if there is a prompt field for the title, summary or content fields.
        // - if a prompt field is present then we need to use it instead of the default config preset.

        if ($request->type === 'image') {
            //dd($request->cover_image);

            try {
                $cover_image_stream = $ai->generateImages("$request->cover_image");
                //dd($cover_image_stream);
                $cover_image = '/images/' . uniqid('aimg_') . '.jpeg';

                Storage::put($cover_image, $cover_image_stream);

                return response()->json(
                    ['success' => true, 'body' => $cover_image]
                );
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'body' => 'Sorry, there has been an error with your request' . $th->getMessage(),
                ]);
            }
        }

        $title_instructions = config('openai.presets.blog.title.instructions');
        $prompt_title = $request->title_prompt ? $request->title_prompt :
            config('openai.presets.blog.title.prompt');
        $summary_prompt = config('openai.presets.blog.summary.prompt');
        $content_prompt = $request->content ? $request->content : config('openai.presets.blog.content.prompt');
        // If the request has a type=title then we need to generate only a title
        if ($request->type === 'title') {
            //dd($request->all());
            $content = json_encode($title_instructions . $prompt_title);
            //dd($content);
            $temperature = $request->has('title_temperature') ? $request->temperature : config('openai.presets.blog.title.temperature');
            $tokens = $request->has('title_tokens') ? $request->tokens : config('openai.presets.blog.title.max_tokens');
        }

        // if the request has a type=summary then we need to generate a title first then a summary.
        if ($request->type === 'summary') {
            //dd($request->all());

            $content = json_encode($request->title ??= $prompt_title . $summary_prompt);
            //dd($content);
            $temperature = $request->has('temperature') ? $request->temperature : config('openai.presets.blog.summary.temperature');
            $tokens = $request->has('tokens') ? $request->tokens : config('openai.presets.blog.summary.max_tokens');
        }

        // if the request has only type=content then we need to generate title, summary and also content.
        if ($request->type === 'content') {
            $content = json_encode(
                ($request->title ??= $title_instructions . $prompt_title) .
                    ($request->summary ??= $summary_prompt) .
                    ($content_prompt)
            );
            $temperature = $request->has('temperature') ? $request->temperature : config('openai.presets.blog.content.temperature');
            $tokens = $request->has('tokens') ? $request->tokens : config('openai.presets.blog.content.max_tokens');
        }

        try {
            $payload = [
                'messages' => $content,
                'temperature' => $temperature,
                'max_tokens' => $tokens,
                'model' => 'gpt-4-vision-preview',
            ];
            $response = $ai->chat($payload);

            return response()->json([
                'success' => true,
                'body' => $response,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'body' => 'Sorry, there has been an error with your request' . $th->getMessage(),
            ]);
        }
    })->name('bloggai'); */

    /* Linkedin Share Routes */
    // Linkedin share oAuth - redirects the user to the linkedin login page to authorize our app.
    Route::get('linkedin/auth', [SocialController::class, 'handleLinkedinAuthentication'])->name('linkedin.auth');
});
Route::get('linkedin/auth/callback', [SocialController::class, 'handleLinkedinCallback']);
