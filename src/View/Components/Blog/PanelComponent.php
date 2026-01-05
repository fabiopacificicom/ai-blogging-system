<?php

namespace PacificDev\BlogAi\View\Components\Blog;

use Illuminate\View\Component;

class PanelComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('pacificdev::blog.components.panel');
    }
}
