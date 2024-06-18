@extends('blog.layouts.admin')

@section('styles')
@vite([
'resources/js/blog/createPostMarkdownEditor.js',
])

@endsection

@section('content')
<div class="container mt-5">
    @include('partials.session')
    @include('partials.validation')
    <h4 class="mb-3 mb-lg-5">👋 Hi {{Auth::user()->name}}, let's create a new amnazing Post! </h4>

    <form action="{{route('admin.posts.store')}}" method="post" id="createPostForm" class="mb-3">
        @csrf
        @method('POST')
        <label for="title">Title</label>


        <div class="mb-3">
            <input type="text" name="title" id="title" class="form-control" placeholder="Post Title here" aria-describedby="titleHelper" value="{{old('title')}}">
        </div>




        <div class="summary">
            <label for="summary">Summary</label>

            <!-- Edit the post summary -->
            <div class="mb-3">
                <div class="card" id="editor_summary">
                </div>
            </div>

            <input type="hidden" id="oldSummary" value="{{old('summary')}}">
            <input type="hidden" name="summary" id="summary">
        </div>

        <div class="content">
            <label for="content">Content</label>

            <!-- Edit the post content -->
            <div class="mb-3">
                <div class="card" id="editor_content">

                </div>
            </div>

            <input type="hidden" id="oldContent" value="{{old('content')}}">
            <input type="hidden" name="content" id="content">

        </div>

        <button class="btn btn-dark bg-black w-100" type="submit">
            Create Post
        </button>

    </form>


</div>
@endsection
