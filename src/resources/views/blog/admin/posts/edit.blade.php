@extends('pacificdev::blog.layouts.admin')

@section('styles')
@vite([
'resources/js/blog/editPostMarkdownEditor.js',
])
@endsection

@section('content')
<div class="container mt-5">
    @include('partials.session')
    @include('partials.validation')
    <h1 class="mb-3 mb-lg-5">Edit: {{$post->title}}</h1>

    <div class="view_post mb-2">
        <a href="{{route('posts.show', $post)}}" class="btn btn-sm">
            <i class="bi bi-file-post"></i> View Post
        </a>
    </div>

    <form action="{{route('admin.posts.update', $post)}}" method="post" id="titleForm" class="mb-3">
        @csrf
        @method('PATCH')
        <label for="title">Title</label>

        <div class="d-flex">
            <div class="input-group mb-3">
                <input type="text" name="title" id="title" class="form-control" placeholder="Post Title here" aria-describedby="titleHelper" value="{{old('title', $post->title)}}">
                <button class="btn btn-outline-secondary" type="submit">Update</button>
            </div>
        </div>
    </form>

    <form action="{{route('admin.posts.update', $post)}}" method="post" id="slugForm" class="mb-3">
        @csrf
        @method('PATCH')
        <label for="slug">Slug</label>
        <div class="d-flex">
            <div class="input-group mb-3">
                <input type="text" name="slug" id="slug" class="form-control" placeholder="Post slug here" aria-describedby="slugHelper" value="{{old('slug', $post->slug)}}">
                <button class="btn btn-outline-secondary" type="submit">Update</button>
            </div>
        </div>
    </form>


    <form action="{{route('admin.posts.update', $post)}}" method="post" id="summaryEditor" class="card border-0 shadow p-2 mb-3">
        @csrf
        @method('PATCH')
        <label for="summary">Summary</label>

        <!-- Edit the post summary -->
        <div class="mb-3">
            <div class="card" id="editor_summary">
                {!! Str::of($post->summary)->markdown() !!}
            </div>
        </div>

        <input type="hidden" id="oldSummary" value="{{ $post->summary }}">
        <input type="hidden" name="summary" id="summary">

        <button class="btn btn-dark" type="submit">
            Update Summary
        </button>

    </form>

    <form action="{{route('admin.posts.update', $post)}}" method="post" id="contentEditor" class="card border-0 shadow p-2 mb-3">
        @csrf
        @method('PATCH')
        <label for="content">Content</label>

        <!-- Edit the post content -->
        <div class="mb-3">
            <div class="card" id="editor_content">
                {!! Str::of($post->content)->markdown() !!}
            </div>
        </div>

        <input type="hidden" id="oldContent" value="{{ $post->content }}">
        <input type="hidden" name="content" id="content">

        <button class="btn btn-dark" type="submit">
            Update content
        </button>

    </form>


</div>
@endsection