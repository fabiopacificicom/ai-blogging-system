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

@endsection


@section('content')

<div class="jumbotron p-5 mb-4 rounded-0" style="min-height:300px;background-size: cover; background-position: center; background-attachment: fixed; background-image: url('{{$post->coverImagePath()}}')">
</div>

<div class="content">
    <div class="container">
        <h1 class="display-5 fw-bold">
            {{$post->title}}
        </h1>
        <p>
            {!! Str::of($post->content)->markdown() !!}
        </p>

        <div class="my-4 p-4 bg-info-subtle rounded-3 d-flex flex-column align-items-center gap-4">
            <img width="100" src="{{asset('fabio_pacifici.jpg' )}}" alt="Fabio Pacifici Profile Image" class="rounded-3">
            <p class="lead">{{__('Hi! I hope you enjoyed this short blog post, for longer tutorials you can find mine also here')}}</p>
            <div class="call_to_action">
                <a class="btn bg-primary-subtle mb-1" href="https://fabiopacifici.com/blog" target="_blank">
                    {{__('Blog')}}
                </a>
                <a class="btn bg-primary-subtle mb-1" href="https://www.youtube.com/c/FabioPacificiHood/videos" target="_blank">
                    {{__('YouTube Channel')}}
                </a>
                <a class="btn bg-primary-subtle mb-1" href="https://www.freecodecamp.org/news/author/fabio/" target="_blank">
                    {{__('FreeCodeCamp Profile')}}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection