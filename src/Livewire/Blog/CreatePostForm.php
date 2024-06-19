<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;

class CreatePostForm extends Component
{
    public function render()
    {
        return view('pacificdev::blog.livewire.posts.create-post-form')->layout('pacificdev::blog.layouts.components-admin');
    }
}
