<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use PacificDev\BlogAi\Models\Post;
use Illuminate\Support\Facades\Artisan;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PostsPage extends Component
{
    use WithPagination;

    public $shareText;

    #[On('searching-posts')]
    public function searchPosts(string $query)
    {


        if (strlen($query) == 0) {
            $posts = Post::orderByDesc('updated_at')->paginate(10);
        } else {
            //dd($query);
            $posts = Post::where('title', 'like', '%' . $query . '%')->orWhere('content', 'like', '%' . $query . '%')->paginate(10);
        }
        $this->posts = $posts;
        //$this->resetPage();
        //dd($this->posts);
    }


    public function share(Post $post)
    {
        //dd($post, $this->shareText);
        Artisan::call('bloggai:share', [
            'social' => 'linkedin',
            'text' => $this->shareText . '...\n' . $post->summary,
            'url' => config('app.url') . '/posts/' . $post->slug
        ]);
    }


    function mount()
    {
        $this->posts = Post::orderByDesc('updated_at')->paginate(10);
    }

    public function render()
    {
        return view(
            'pacificdev::blog.livewire.posts.posts-page',
            [
                'posts' => $this->posts
            ]
        )->layout('pacificdev::blog.layouts.components-admin');
    }
}
