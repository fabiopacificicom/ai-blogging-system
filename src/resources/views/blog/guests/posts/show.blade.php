@extends('pacificdev::blog.layouts.app')

@section('styles')
<meta name="description" content="{{$post->summary}}">
<meta name="keywords" content="programming, fullstack, ai, openai, chatgpt, content, web development">
<!-- For LinkedIn: -->

<meta property="og:title" content="{{$post->title}}">
<meta property="og:description" content="{{$post->summary}}">
<meta property="og:image" content="{{asset('storage' . $post->cover_image)}}">
<meta property="og:url" content="{{route('posts.show', $post->id)}}">
<meta property="og:type" content="website">
<!-- For Twitter: -->

<meta name="twitter:title" content="{{$post->title}}">
<meta name="twitter:description" content="{{$post->summary}}">
<meta name="twitter:image" content="{{asset('storage' . $post->cover_image)}}">
<meta name="twitter:card" content="summary_large_image">

<script>
    document.addEventListener('alpine:navigated', () => {
        Prism.highlightAll()
    })
</script>
<style>
    .jumbotron {
        min-height: 75dvh;
        background-attachment: fixed;
    }

    #post {
        .post-title {
            margin-top: -5rem;
            padding: 1rem;
            background: white;
            color: #333;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .content {
            max-width: 100%;
            margin: auto;
            background-color: #33333333;
            padding: 1rem;
        }
    }
</style>
@endsection


@section('content')

<div class="jumbotron p-5 mb-4 rounded-0" style="background-image: url('{{$post->coverImagePath()}}')">
</div>

<div id="post">
    <div class="container">
        <h1 class="post-title display-5 fw-bold">
            {{$post->title}}
        </h1>
        <div class="content">
            {!! Str::of($post->content)->markdown() !!}
        </div>

        <div class="my-4 p-4 bg-info-dark rounded-3 d-flex flex-column align-items-center gap-4">
            <img width="60" src="{{asset('images/vendor/pacificdev/blog-ai/logo.png' )}}" alt="logo" class="rounded-3">
            <p class="lead">{{__('Find us online ')}}</p>
            <div class="call_to_action">
                <a class="btn rounded-pill bg-primary-subtle mb-1" href="https://fabiopacifici.com/blog" target="_blank">
                    {{__('Blog')}}
                </a>
                <a class="btn rounded-pill bg-primary-subtle mb-1" href="https://www.youtube.com/c/FabioPacificiHood/videos" target="_blank">
                    <i class="bi bi-youtube"></i> {{__('YouTube Channel')}}
                </a>
                <a class="btn rounded-pill bg-primary-subtle mb-1" href="https://www.freecodecamp.org/news/author/fabio/" target="_blank">
                    {{__('FreeCodeCamp Profile')}}
                </a>
                <a class="btn rounded-pill bg-primary-subtle mb-1" href="https://github.com/fabiopacificicom" target="_blank">
                    <i class="bi bi-github"></i> {{__('Github')}}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection