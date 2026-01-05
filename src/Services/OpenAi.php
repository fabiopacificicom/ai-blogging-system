<?php

namespace PacificDev\BlogAi\Services;

use Exception;
use Illuminate\Support\Facades\Http;

/* TODO: This service should be replaced by a dedicated package services when ready 
https://github.com/fabiopacificicom/ai-providers-for-laravel

*/

class OpenAi
{
    private $timeout = 0;

    private $API_KEY;
    private $GPT_MODEL_4;
    private $IMAGES_ENDPOINT;
    private $CHAT_ENDPOINT;
    private $IMAGE_MODEL;
    public function __construct()
    {
        $this->API_KEY = config('bloggai.openai.api_key');
        $this->GPT_MODEL_4 = 'gpt-4-vision-preview';
        $this->IMAGES_ENDPOINT = config('bloggai.openai.endpoints.images.create');
        $this->CHAT_ENDPOINT = config('bloggai.openai.endpoints.chat.completations');
        $this->IMAGE_MODEL = 'dall-e-3';
    }


    public function chat(array $payload)
    {
        if (!array_key_exists('model', $payload)) {
            $payload['model'] = $this->GPT_MODEL_4;
        }
        if (!is_array($payload) || !array_key_exists('messages', $payload)) {
            throw new Exception('Ops! 🤯 something wrong happend! The payload is required to call the chat method');
        }

        $data = [
            'api_endpoint' => $this->CHAT_ENDPOINT,
            'payload' => $payload,
        ];

        //dd($data);
        return $this->baseRequest($data);
    }


    public function generateImages($prompt = 'Generate an image of a black cat')
    {
        // call the api endpoint

        // handle the response and return the generated image
        try {
            $r = Http::withToken($this->API_KEY)->timeout($this->timeout)
                ->post(
                    $this->IMAGES_ENDPOINT,
                    [
                        'prompt' => $prompt,
                        'n' => 1,
                        'model' => $this->IMAGE_MODEL,
                        'size' => '1024x1024',
                        'response_format' => 'b64_json',
                    ]
                );

            /* TODO: Need to manage the error better. When inserting an incorrect api key the core returns the stack trace referring to the choices key being null. */
            if ($r->successful()) {
                //dd(json_decode($r->body(), true)['data']);
                $image_b64 = json_decode($r->body(), true)['data'][0]['b64_json'];

                // retunr the response
                $image = base64_decode($image_b64);

                return $image;
            }

            $r->onError(function ($error) {
                $error_array = json_decode($error->body(), true);
                //var_dump($error_array['error']['message']);
                exit($error_array['error']['message']);
            });
        } catch (Exception $e) {
            return $e;
        }
    }


    public function getAnswer($response): ?string
    {
        if ($response->successful()) {
            $answerText = json_decode($response->body(), true)['choices'][0]['message']['content'];

            // return the response
            return $answerText;
        }

        return null;
    }

    public function getFailureMessage($response)
    {
        if ($response->clientError()) {
            return json_decode($response->body(), true)['error']['message'];
        }
        if ($response->serverError()) {
            return json_decode($response->body(), true);
        }

        //return json_decode($response->body(), true);
        return null;
    }

    /**
     * Sends a base HTTP request to the specified API endpoint with the provided data and API key.
     *
     * @param  array  $params An array containing the API endpoint, payload, and API key.
     * @return mixed The response from the API endpoint.
     */
    private function baseRequest($params)
    {
        return Http::withToken($this->API_KEY)->timeout($this->timeout)->post($params['api_endpoint'], $params['payload']);
    }
}
