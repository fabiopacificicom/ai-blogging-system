<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;
use PacificDev\BlogAi\Models\Post;
use PacificDev\BlogAi\Services\OpenAi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PacificDev\BlogAi\Traits\Postable;

class EditPostForm extends Component
{
    use Postable;
    public $prompt = '';
    public $imagePrompt = '';
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
        $this->draft = [
            'title' => $post->title,
            'content' => $post->content
        ];
        $this->post = $post;
        $this->prompt = $post->content;
        $this->imagePath = $post->cover_image;
        $this->model_name = config('bloggai.presets.blog.default_model');
        $this->max_tokens = config('bloggai.presets.blog.max_post_length');
    }

    public function updated($name, $value) 
    {
        // if the form input under update is the title
        // we need to regenerate also its slug
        if($name == 'title') {

            $this->validate([
                'title' => 'unique|posts,title,except:id=' . $this->post->id
            ]);
            
            $this->post->update(['slug'=> Str::slug($value) ]);
        }

        $this->post->update([
            $name => $value,
        ]);
    }

    public function unpublish(){
        $this->post->status = 'draft';
    }

}
