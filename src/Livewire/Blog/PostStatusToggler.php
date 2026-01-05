<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use PacificDev\BlogAi\Models\Post;
use Livewire\Component;

class PostStatusToggler extends Component
{


    public $isPublic;
    public $post;

    function updated($property)
    {
        if ($property === 'isPublic') {
            $this->post->update(['status' => $this->isPublic ? 'public' : 'draft']);
            //dd($this->post);
        }
    }


    function mount(Post $post)
    {
        $this->post = $post;
        $this->isPublic = $post->status === 'public' ? true : false;
    }



    public function render()
    {
        return <<<'HTML'
        <div class="post-status-toggler" style="cursor:pointer">
            <div class="d-flex">
                <input class="form-checkbox d-none" type="checkbox" name="status" id="status-{{$post->id}}" wire:model.live="isPublic">
                <label for="status-{{$post->id}}" class="btn badge {{$isPublic == 'public' ? 'bg-success' : 'bg-danger'}}">{{$isPublic  ? 'Public' : 'Draft'}}</label>
            </div>
        </div>
        HTML;
    }
}
