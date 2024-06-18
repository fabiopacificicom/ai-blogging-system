<?php

namespace PacificDev\BlogAi\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePostRequest;
use PacificDev\BlogAi\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PacificDev\LaravelOpenAi\Services\OpenAi;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->paginate(12);

        return view('bloggai::blog.admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('bloggai::blog.admin.posts.create');
    }

    public function store(Request $request, OpenAi $ai)
    {
        //dd($request->all());
        /* When the request has no title, summary or content
        we call the artisan command using its default preset
        as defined in the bloggai configuration file config/bloggai.php
        */
        if (!$request->hasAny('title', 'summary', 'content')) {
            // TODO:
            // before calling the Artisan command,must
            // check what if the parameters are in the request.
            // or if the values are stored as blog settings
            // otherwise use the default preset in the openai.php config file
            Artisan::call('bloggai:post', [
                $request->title_temperature,
                $request->title_max_tokens,
                $request->title_instructions,
                $request->summary_temperature,
                $request->summary_max_tokens,
                $request->summary_prompt,
                $request->content_temperature,
                $request->content_max_tokens,
            ]);
        } else {
            // if the cover_image field is empty, generate a random image to use
            if (!$request->has('cover_image')) {
                $cover_image_stream = $ai->generateImages('Developer in a dark room with a purple and blue backlight, multi monitor setup with nice ui, 3D render.');
                $cover_image = '/images/' . uniqid('aimg_') . '.jpeg';

                Storage::put($cover_image, $cover_image_stream);
                $request->cover_image = $cover_image;
            }
            // TODO: handle what to do when the request has a cover image file passed to it
            $data = [
                'title' => $request->title,
                'author' => Str::limit(Auth::user()->name, 10, ''),
                'slug' => Str::slug($request->title),
                'summary' => $request->summary,
                'content' => $request->content,
                'cover_image' => $request->cover_image,
            ];

            Post::create($data);
        }

        return to_route('admin.posts.index')->with('message', 'Post Generated Successfully');
    }

    public function settings(Request $request)
    {
        dd($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('bloggai::blog.admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        /* TODO:
        At mo you can only update the post title and not the body.
        replace the text area with an actual markdown text editor to enable posts updates*/

        //dd($request->all());
        $val_data = $request->validated();
        $val_data['status'] = 'public';
        if ($request->has('slug')) {
            $val_data['slug'] = $request->input('slug');
        }
        //dd($val_data);

        $post->update($val_data);

        return to_route('admin.posts.edit', $post)->with('message', "Post: $post->id Updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return to_route('admin.posts.index')->with('message', 'Post Deleted successfully');
    }
}
