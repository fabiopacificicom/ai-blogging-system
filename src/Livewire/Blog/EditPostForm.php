<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;
use PacificDev\BlogAi\Models\Post;
use PacificDev\BlogAi\Services\OpenAi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PacificDev\BlogAi\Traits\Postable;

class EditPostForm extends Component
{
    use Postable;


    public function render()
    {

        return view('pacificdev::blog.livewire.posts.edit-post-form')->layout('pacificdev::blog.layouts.components-admin');
    }
    public function mount(Post $post)
    {
        //dd($post);
        $this->draft = [
            'title' => $post->title,
            'content' => $post->content,
            'summary' => $post->summary
        ];
        //dd($this->draft);
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->imagePath = $post->cover_image;
        $this->model_name = config('bloggai.presets.blog.default_model');
        $this->max_tokens = config('bloggai.presets.blog.max_post_length');
    }

    public function updated($name, $value)
    {

        //dd($name, $value);
        // if the form input under update is the title
        // we need to regenerate also its slug

        if($name == 'cover_image') {
            $value = $this->imagePath;
        }

        //dd($name, $value);

        if ($name == 'title') {

            $this->validate([
                'title' => 'unique:posts,title,except,' . $this->post->id
            ]);

            $this->post->update(['slug' => Str::slug($value), 'title' => $value]);
            // redirect to the new edited post
            return to_route('admin.posts.edit', $this->post)->with('message', 'Title and slug updated');

        } elseif($name !== 'temp' && $name !== 'model' && $name !== 'max_tokens') {

            $this->post->update([
                $name => $value,
            ]);
            session()->flash('message', "$name updated");
        }
    }

    public function togglePublish()
    {
        if ($this->post->status === 'draft') {
            $this->post->update(['status' => 'public']);
        } else {
            $this->post->update(['status' => 'draft']);
        }
    }
}
