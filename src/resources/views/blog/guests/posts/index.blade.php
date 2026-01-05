@extends('pacificdev::blog.layouts.app')

@section('styles')

<style>
    [x-cloak] {
        display: none !important;
    }

    @media (prefers-color-scheme: light) {
        main {
            background-color: #ededed;
        }
    }

    .jumbotron {
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        min-height: 60dvh;

        h1 {
            @media (min-width: 1200px) {
                font-size: 4rem;
            }
        }

        .lead {
            font-size: 1.25rem;
            line-height: 1.5rem;
        }
    }

    .content {
        background-image: linear-gradient(48deg, #060826, transparent);
    }

    .card {
        img {
            filter: opacity(0.4);
        }

        .title {
            font-size: 20px;
        }
    }
</style>
@endsection


@section('content')

<div class="jumbotron p-5 bg-black text-white rounded-0" style="background-image: url('{{ $posts[count($posts) - 1]?->coverImagePath() }}');" x-data x-cloak>

    <div class="container py-5">
        <h1 class="display-3 fw-bold">
            Bite-Sized Reads
        </h1>
        <p class="lead">
            Short and sweet reads that take less than 10 minutes of your busy day!
        </p>

        <form action="" method="get" class="d-flex gap-2 align-items-center">
            <div class="mb-0 w-100">
                <input type="search" class="form-control form-control-lg rounded-pill" name="searchPost" id="searchPost" aria-describedby="helpId" placeholder="search something..." />
            </div>
            <button type="submit" class="btn btn-dark rounded-pill">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="content py-4">
    <div class="container">


        @if(isset($_GET['searchPost']) && !empty($_GET['searchPost']))
        <h3>Showing results for: <strong>{{$_GET['searchPost']}}</strong></h3>
        @endif


        {{$posts->links('vendor.pagination.bootstrap-5')}}
        <div class="row row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 g-4 my-4">


            @forelse($posts as $index => $post)
            @unless ($post->status === 'draft')
            <div class="col-12">
                <div class="card rounded-4 shadow h-100 position-relative">
                    <img style="object-fit: cover" width="320" height="320" src="{{ asset('storage' . $post->cover_image) }}" loading="lazy" alt="Post {{$post->title}} Cover Image" class="card-img-top rounded-4">
                    <div class="card-body position-absolute w-100 h-100 d-flex flex-column justify-content-between">

                        <div class="datails">
                            <div class="metadata">
                                <div class="fs_sm">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</div>
                            </div>
                            <h3 class="title">{{$post->title}}</h3>
                            <p class="text-truncate">{{$post->summary}}</p>
                        </div>


                        <div class="actions">
                            <a wire:navigate class="btn btn-dark d-block rounded-pill" href="{{ route('posts.show', $post ) }}">Read more <i class="bi bi-box-arrow-right"></i></a>
                            @auth
                            <a wire:navigate class="btn btn-dark d-block rounded-pill mt-1" href="{{ route('admin.posts.edit', $post ) }}">Edit <i class="bi bi-pencil"></i></a>
                            @endauth
                        </div>
                    </div>

                </div>
            </div>
            @endunless
            @empty
            <div class="col-12">
                <p>
                    😱 Sorry nothing to see here
                </p>
            </div>
            @endforelse
        </div>
        {{$posts->links('vendor.pagination.bootstrap-5')}}


    </div>
</div>
@endsection