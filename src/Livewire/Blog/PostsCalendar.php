<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;
use PacificDev\BlogAi\Models\Setting;

class PostsCalendar extends Component
{
    public $postGenerationDays = [];
    public $postGenerationTime;
    public $postShareDays = [];
    public $postShareTime;

    public function mount()
    {
        //dd(Setting::get('post_generation_schedule_time'));
        // Load the current settings from the database for both commands
        $this->postGenerationDays = is_array(Setting::get('post_generation_schedule_days'))
        ? Setting::get('post_generation_schedule_days')
            : [];
        
        $this->postGenerationTime = Setting::get('post_generation_schedule_time', '09:00');

        $this->postShareDays = is_array(Setting::get('post_share_schedule_days'))
        ? Setting::get('post_share_schedule_days')
        : [];
        
        
        $this->postShareTime = Setting::get('post_share_schedule_time', '13:00');


        //dd($this->postGenerationDays, $this->postGenerationTime, $this->postShareDays, $this->postShareTime);
    }

    public function saveSchedule()
    {
        // Validate the input for both commands
        $this->validate([
            'postGenerationDays' => 'required|array|min:1',
            'postGenerationTime' => 'required|date_format:H:i',
            'postShareDays' => 'required|array|min:1',
            'postShareTime' => 'required|date_format:H:i',
        ]);

        // Save the settings to the database for both commands
        Setting::set('post_generation_schedule_days', $this->postGenerationDays);
        Setting::set('post_generation_schedule_time', $this->postGenerationTime);
        Setting::set('post_share_schedule_days', $this->postShareDays);
        Setting::set('post_share_schedule_time', $this->postShareTime);

        // Provide feedback to the user
        session()->flash('message', 'Schedules updated successfully.');
    }


    public function render()
    {
        return view(
            'pacificdev::blog.livewire.posts.calendar',
        )->layout('pacificdev::blog.layouts.components-admin');
    }
}
