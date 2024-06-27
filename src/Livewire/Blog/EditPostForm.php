<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;
use PacificDev\BlogAi\Models\Post;
use PacificDev\BlogAi\Services\OpenAi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditPostForm extends Component
{

    public $prompt = '';
    public $imagePrompt = 'Developer i a dark room';
    public $imagePath;
    public $draft = [];
    public $error = [];
    public $temp = 0.4;
    public $max_tokens = 2500;
    public $model_name;
    public Post $post;


    public function render()
    {
    
        return view('pacificdev::blog.livewire.posts.edit-post-form')->layout('pacificdev::blog.layouts.components-admin');
    }
    public function mount(Post $post)
    {
        $this->post = $post;
        $this->prompt = $post->content;
        $this->model_name = config('bloggai.presets.blog.default_model');
        $this->max_tokens = config('bloggai.presets.blog.max_post_length');
    }

}
