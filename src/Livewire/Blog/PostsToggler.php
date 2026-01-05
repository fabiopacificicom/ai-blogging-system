<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use PacificDev\BlogAi\Models\Post;
use Livewire\Component;

class PostsToggler extends Component
{
    public $publishPosts;

    public function updatedPublishPosts($public)
    {
        if ($public) {
            Post::query()->update(['status' => 'public']);
        } else {
            Post::query()->update(['status' => 'draft']);
        }
    }

    public function mount()
    {

        $this->publishPosts = true;
        if (Post::where('status', 'public')->count() === 0) {
            $this->publishPosts = false;
        }
    }

    public function render()
    {
        return view('pacificdev::blog.livewire.posts.posts-toggler');
    }
}
