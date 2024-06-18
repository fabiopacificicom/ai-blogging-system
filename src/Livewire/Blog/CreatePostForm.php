<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;

class CreatePostForm extends Component
{
    public function render()
    {
        return view('bloggai::blog.livewire.posts.create-post-form')->layout('bloggai::blog.layouts.components-admin');
    }
}
