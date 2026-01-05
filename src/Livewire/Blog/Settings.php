<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Arr;
use PacificDev\BlogAi\Models\Topic;
use Illuminate\Support\Str;
use PacificDev\BlogAi\Jobs\ProcessImageOptimization;
use Illuminate\Support\Facades\Storage;
class Settings extends Component
{

    #[Rule('required')]
    public $topics;
    #[Rule('nullable')]
    public $inRandomOrder;
    public $imagesOptimizationStatus = false;


    public function mount()
    {

        $topics = Topic::where('active', 1)->orderByDesc('updated_at')->get();


        $this->inRandomOrder = true;

        if ($topics->count() === 0) {
            $this->topics = Arr::join(config('bloggai.presets.blog.topics'), ",\n");
        } else {
            $this->topics =  Arr::join($topics->pluck('name')->toArray(), "\n");
        }

        //dd($this->topics);
    }



    public function render()
    {
        return view('pacificdev::blog.livewire.settings')->layout('pacificdev::blog.layouts.components-admin');
    }

    
    public function updateTopicsList()
    {
        // validate the user inputs
        $this->validate();

        // Explode the string into an array of topics
        $submittedTopics = explode(PHP_EOL, $this->topics);

        // Normalize the topics by trimming whitespace and removing empty values
        $submittedTopics = array_filter(array_map('trim', $submittedTopics));

        // Convert the topics to slugs for comparison
        $submittedSlugs = array_map(function ($topic) {
            return Str::slug($topic);
        }, $submittedTopics);

        // Activate or create new topics based on the submitted list
        foreach ($submittedTopics as $topic) {
            Topic::updateOrCreate(
                ['slug' => Str::slug($topic)],
                ['name' => $topic, 'active' => true]
            );
        }

        // Deactivate topics not in the submitted list
        Topic::whereNotIn('slug', $submittedSlugs)->update(['active' => false]);

        // Redirect after updating
        $this->redirect('/blog-ai/settings', true);
    }

    public function optimizeImages()
    {

        $images = Storage::allFiles('images/');
        $this->imagesOptimizationStatus = 'processing';
        foreach ($images as $image_path) {
            ProcessImageOptimization::dispatch($image_path);
        }
        $this->imagesOptimizationStatus = 'completed';
    }

}
