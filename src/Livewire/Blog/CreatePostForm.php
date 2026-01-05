<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;
use PacificDev\BlogAi\Traits\Postable;

class CreatePostForm extends Component
{
    use Postable;

   
    public function render()
    {
        return view('pacificdev::blog.livewire.posts.create-post-form')->layout('pacificdev::blog.layouts.components-admin');
    }
    public function mount()
    {  
        $this->model_name = config('bloggai.presets.blog.default_model');
        $this->max_tokens = config('bloggai.presets.blog.max_post_length');
    }

    public function updated($property)
    {
        if ($property === 'draft') {
            dd($this->draft);
            $this->content = in_array('content', $this->draft) ? $this->draft['content'] : $this->content;
        }
    }

    
}
