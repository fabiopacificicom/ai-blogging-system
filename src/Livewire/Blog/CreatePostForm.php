<?php

namespace PacificDev\BlogAi\Livewire\Blog;

use Livewire\Component;
use PacificDev\BlogAi\Models\Post;
use PacificDev\BlogAi\Services\OpenAi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreatePostForm extends Component
{

    public $content = '';
    public $cover_image = 'Developer i a dark room';
    public $imagePath;
    public $draft = [];
    public $error = [];
    public $temp = 0.4;
    public $max_tokens = 2500;
    public $model_name;
    public $post;


    public function render()
    {
        return view('pacificdev::blog.livewire.posts.create-post-form')->layout('pacificdev::blog.layouts.components-admin');
    }
    public function mount()
    {
        $this->model_name = config('bloggai.presets.blog.default_model');
        $this->max_tokens = config('bloggai.presets.blog.max_post_length');
    }

    public function updated($property)
    {
        if ($property === 'draft') {
            dd($this->draft);
            $this->content = in_array('content', $this->draft) ? $this->draft['content'] : $this->content;
        }
    }

    public function generateImage(OpenAi $ai)
    {
        //dd($this->cover_image);
        try {
            $cover_image_stream = $ai->generateImages("$this->cover_image");
            //dd($cover_image_stream);
            $cover_image = '/images/' . uniqid('aimg_') . '.jpeg';

            Storage::put($cover_image, $cover_image_stream);

            $this->imagePath = $cover_image;
            $this->draft['cover_image'] = $this->imagePath;
        } catch (\Throwable $th) {
            $this->error['image'] = 'Sorry, there has been an error with your request' . $th->getMessage();
        }
    }

    public function generateDraft(OpenAi $ai)
    {
        // validate the prompts
        $this->validate(
            [
                'content' => ['required', 'min:5', 'max:' . intVal($this->max_tokens), 'unique:posts,title,except,' . $this->post?->id ],
                'cover_image' => 'required'
            ]
        );
        $payload = $this->setPayload();    
        // get the response
        //dd($payload);
        $response =  $ai->chat($payload);
        // handle errors
        $this->handleErrorsGracefully($response->json());
        // generate post
        $this->generatePost($response->json());
    }

    public function publish(OpenAi $ai)
    {
        
        // IF
        // 1. if NO draft was generated, generate the draft
        if (empty($this->draft)) {
            $this->generateDraft($ai);
        }
        // 2. if NO image path was saved generate the image and get its path
        if (!$this->imagePath) {
            $this->generateImage($ai);
        }
        // 3. save the cover_image path
        $this->draft['cover_image'] = $this->imagePath;
        // Set the article to public
        $this->draft['status'] = 'public';
        $this->draft['content'] = $this->content;
        $post = Post::find($this->post->id);
        $post->update($this->draft);
        return back()->with('message', 'Post Published');
    }



    private function generatePost($responseArray){
        
        $postData = json_decode($responseArray['choices'][0]['message']['content'], true);
        //dd($postData);
        $this->draft = $postData;
        $this->content = $this->draft['content'];
        // store the draft in the database

        if(!$this->post) {
            $this->post = Post::create($postData);
        } else {
            $this->post->update($postData);
        }
        return back()->with('message', 'Post Generation completed');
    }

    private function handleErrorsGracefully($responseArray){
        //dd($responseArray);
        $this->error = [];
        // extract the post json as an array
        if (array_key_exists('error', $responseArray)) {
            $this->error['message'] = $responseArray['error']['message'];
            Log::error($this->error['message']);

            return back()->with('message', $this->error['message']);
        }
    }

    private function setPayload()
    {
         //set the audience
        $audience = config('bloggai.blog.target_audence');
        
        // set the payload
        $payload = [
            'model' => $this->model_name,
            'temperature' => floatVal($this->temp),
            'max_tokens' => intVal($this->max_tokens),
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                config('bloggai.presets.system'),
                config('bloggai.presets.blog.create'),
                [
                    'role' => 'user',
                    'content' => "
                    ## Audience\n\n $audience \n\n 
                    ## Instructions \n\n" . $this->content
                ]
            ]
        ];
        return $payload;
    }
}
