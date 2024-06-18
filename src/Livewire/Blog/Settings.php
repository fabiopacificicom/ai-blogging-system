<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Arr;
use App\Models\Topic;
use Illuminate\Support\Str;

class Settings extends Component
{

    #[Rule('required')]
    public $topics;
    #[Rule('nullable')]
    public $inRandomOrder;



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
        return view('bloggai::blog.livewire.settings')->layout('bloggai::blog.layouts.components-admin');
    }
}
