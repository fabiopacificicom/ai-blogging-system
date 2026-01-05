<?php

namespace App\Http\Controllers\Blog\Guest;

use App\Http\Controllers\Controller;
use PacificDev\BlogAi\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::where('status', 'public')->orderByDesc('id')->paginate(12);
        // disable to disable auto login on the posts page for the site superadmin id 1
        app()->isProduction() === false ? Auth::login(User::find(1)) : '';

        if ($request->has('searchPost')) {
            $posts = Post::search($request->searchPost)->paginate(12);
        }

        return view('pacificdev::blog.guests.posts.index', compact('posts'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {

        if ($post->status == 'public') {
            return view('pacificdev::blog.guests.posts.show', compact('post'));
        }
        abort('404');
    }
}
