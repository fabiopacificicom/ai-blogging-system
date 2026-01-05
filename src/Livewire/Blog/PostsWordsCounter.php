<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PostsWordsCounter extends Component
{
    public function render()
    {

        $this->totalWords = DB::table('posts')
            ->select(DB::raw('SUM(LENGTH(content) - LENGTH(REPLACE(content, " ", "")) + 1
                          + LENGTH(title) - LENGTH(REPLACE(title, " ", "")) + 1
                          + LENGTH(summary) - LENGTH(REPLACE(summary, " ", "")) + 1) as total_words'))
            ->first()
            ->total_words;

        return <<<'HTML'

        <div>{{ $this->totalWords }}</div>

        HTML;
    }
}
