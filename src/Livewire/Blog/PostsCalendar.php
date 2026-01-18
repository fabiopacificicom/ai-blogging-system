<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;
use PacificDev\BlogAi\Models\Setting;
use Illuminate\Validation\Rule;
class PostsCalendar extends Component
{
    public $postGenerationDays = [];
    public $postGenerationTime;
    public $postShareDays = [];
    public $postShareTime;
    public $postShareTimes = []; // Array of times for multiple shares per day
    public $schedulerTimezone = 'Europe/Rome';
    public $maxSharesPerDay = 3;

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
        
        // Load timezone
        $this->schedulerTimezone = Setting::get('scheduler_timezone', 'Europe/Rome');
        
        // Load max shares per day and share times array
        $this->maxSharesPerDay = Setting::get('linkedin.max_shares_per_day', 3);
        $this->postShareTimes = Setting::get('post_share_schedule_times', []);
        
        // Initialize share times if empty (spread evenly across day)
        if (empty($this->postShareTimes) && $this->maxSharesPerDay > 0) {
            $this->postShareTimes = $this->generateDefaultShareTimes($this->maxSharesPerDay);
        }

        //dd($this->postGenerationDays, $this->postGenerationTime, $this->postShareDays, $this->postShareTime);
    }
    
    private function generateDefaultShareTimes(int $count): array
    {
        $times = [];
        $startHour = 9;
        $endHour = 18;
        $interval = ($endHour - $startHour) / max(1, $count);
        
        for ($i = 0; $i < $count; $i++) {
            $hour = (int)($startHour + ($i * $interval));
            $times[] = sprintf('%02d:00', $hour);
        }
        
        return $times;
    }

    public function rules()
    {
        return [
            'postGenerationDays' => ['required', 'array'],
            'postGenerationTime' => 'required|date_format:H:i',
            'postShareDays' => ['required', 'array'],
            'postShareTime' => 'required|date_format:H:i',
            'schedulerTimezone' => 'required|timezone',
            'postShareTimes.*' => 'nullable|date_format:H:i',
            'maxSharesPerDay' => 'required|integer|min:1|max:10',
        ];
    }

    public function messages()
    {
        return [
            'postGenerationDays.required' => 'Post Generation days: plase select at least one day from the calendar',
            'postGenerationDays.array' => 'The days must be a list of numbers each representing a day in the week starting rom 0 to 6.',
            'postShareDays.required' => 'Post Share days: plase select at least one day from the calendar',
            'postShareDays.array' => 'The days must be a list of numbers each representing a day in the week starting rom 0 to 6.',
            'postGenerationTime.required' => 'This field is required',
            'postShareTime.required' => 'This field is required',
        ];
    }
    
    public function saveSchedule()
    {
        // Validate the input for both commands
        $this->validate();

        // Normalize boolean arrays to index arrays (convert [0=>true,1=>false,2=>true] to [0,2])
        $genDays = array_keys(array_filter($this->postGenerationDays, fn($v) => $v === true || $v === '1' || $v === 1));
        $shareDays = array_keys(array_filter($this->postShareDays, fn($v) => $v === true || $v === '1' || $v === 1));

        // Filter and clean share times
        $cleanShareTimes = array_values(array_filter($this->postShareTimes, fn($t) => !empty($t)));
        
        // Save the settings to the database for both commands
        Setting::set('post_generation_schedule_days', $genDays);
        Setting::set('post_generation_schedule_time', $this->postGenerationTime);
        Setting::set('post_share_schedule_days', $shareDays);
        Setting::set('post_share_schedule_time', $this->postShareTime);
        Setting::set('scheduler_timezone', $this->schedulerTimezone);
        Setting::set('post_share_schedule_times', $cleanShareTimes);
        Setting::set('linkedin.max_shares_per_day', $this->maxSharesPerDay);

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
