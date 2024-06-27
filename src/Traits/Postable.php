<?php

namespace PacificDev\BlogAi\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

trait Postable
{
    public function generateImage(OpenAi $ai)
    {
        //dd($this->imagePrompt);
        try {
            $cover_image_stream = $ai->generateImages("$this->imagePrompt");
            //dd($cover_image_stream);
            $cover_image = '/images/' . uniqid('aimg_') . '.jpeg';

            Storage::put($cover_image, $cover_image_stream);

            $this->imagePath = $cover_image;
        } catch (\Throwable $th) {
            $this->error['image'] = 'Sorry, there has been an error with your request' . $th->getMessage();
        }
    }

    public function generateDraft(OpenAi $ai)
    {
        // validate the prompts
        $this->validate(
            [
                'prompt' => 'required|min:5|max:' . $this->max_tokens,
                'imagePrompt' => 'required'
            ]
        );
        $payload = $this->setPayload();    
        // get the response
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
        $this->draft['content'] = $this->prompt;
        $post = Post::find($this->post->id);
        $post->update($this->draft);
        return back()->with('message', 'Post Published');
    }



    private function generatePost($responseArray){
        $postData = json_decode($responseArray['choices'][0]['message']['content'], true);
        //dd($postData);
        $this->draft = $postData;
        $this->prompt = $this->draft['content'];
        // store the draft in the database

        $this->post = Post::create($postData);
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
            'temperature' => intVal($this->temp),
            'max_tokens' => $this->max_tokens,
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                config('bloggai.presets.system'),
                config('bloggai.presets.blog.create'),
                [
                    'role' => 'user',
                    'content' => "
                    ## Audience\n\n $audience \n\n 
                    ## Instructions \n\n" . $this->prompt
                ]
            ]
        ];
        return $payload;
    }

}
