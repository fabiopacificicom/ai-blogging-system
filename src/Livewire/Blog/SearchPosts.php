<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;
use PacificDev\BlogAi\Livewire\Blog\PostsPage;



class SearchPosts extends Component
{

    public $query;

    function updated($property)
    {
        //dd($property);
        if ($property === 'query') {
            //dd($property, $this->query);
            $this->dispatch('searching-posts', $this->query)->to(PostsPage::class);
        }
    }

    public function render()
    {
        return <<<'HTML'
        <div x-data="{showSearch: false}">

                <button class="btn btn-outline" x-on:click="showSearch = !showSearch">
                    <i class="bi bi-search"></i>
                </button>


                    <div class="w-100 pt-3 pb-4" x-show="showSearch">
                         <input name="query" type="text" class="form-control position-absolute start-0 top-1" aria-label="Button" aria-describedby="" placeholder="Start typing" wire:model.live.debounce.500ms="query" x-transition>
                    </div>

        </div>
        HTML;
    }
}
