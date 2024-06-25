<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;
use PacificDev\BlogAi\Services\OpenAi;

class CreatePostForm extends Component
{

    public $prompt = '';
    public $imagePrompt = 'Developer i a dark room';
    public $imagePath;
    public $draft = [];
    public $error = [];
    public $temp = 0.4;
    public $max_tokens = 2500;
    public $model_name;




    public function render()
    {
        return view('pacificdev::blog.livewire.posts.create-post-form')->layout('pacificdev::blog.layouts.components-admin');
    }
    public function mount()
    {
        $this->model_name = config('bloggai.presets.blog.default_model');
        $this->max_tokens = config('bloggai.presets.blog.max_post_length');
    }
    public function generateImage(OpenAi $ai)
    {
        try {
            $cover_image_stream = $ai->generateImages("$this->imagePrompt");
            //dd($cover_image_stream);
            $cover_image = '/images/' . uniqid('aimg_') . '.jpeg';

            Storage::put($cover_image, $cover_image_stream);

            $this->imagePath = $cover_image;
        } catch (\Throwable $th) {
            $this->error['image'] = 'Sorry, there has been an error with your request' . $th->getMessage();
        }
    }

    public function generateDraft(OpenAi $ai)
    {

        $audience = config('bloggai.presets.blog.target_audence');
        // given the prompt generate:

        $ai->chat([
            'model' => $this->model_name,
            'temperature' => $this->temp,
            'max_tokens' => $this->max_tokens,
        ]);
        // - the post title
        // - the post content
        // - the post summary
        // - the image unless there is an imagePath already stored.
        // return the post id
    }

    public function publish(OpenAi $openAi)
    {
        // publish the article - set to public
        // updathe the post fields in case the user made any change
    }
}
