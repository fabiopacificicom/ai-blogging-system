@extends('blog.layouts.admin')

@section('content')
<div class="container mt-5">

    <div class="row row-cols-1 row-cols-sm-3  row-cols-lg-4 g-3">

        <x-panel class="col" route="#" title="TotalWords" value="">
            <x-slot name="icon">
                <i class="bi bi-card-text"></i>
            </x-slot>
            <h3>
                <livewire:posts-words-counter></livewire:posts-words-counter>
            </h3>
        </x-panel>


        <x-panel class="col" route="#" title="AI writes" value="Generate" data-bs-toggle="modal" data-bs-target="#create-post-modal">
            <x-slot name="icon">
                <i class="bi bi-body-text"></i>
            </x-slot>
            @include('partials.posts.create-modal')
        </x-panel>


        <x-panel class="col" route="{{route('admin.posts.create')}}" title="You write" value="Write">
            <x-slot name="icon">
                <i class="bi bi-plus-circle-fill"></i>
            </x-slot>
        </x-panel>

        <x-panel class="col" route="{{route('admin.blog.settings')}}" title="Settings" value="Customize">
            <x-slot name="icon">
                <i class="bi bi-sliders2-vertical"></i>
            </x-slot>
        </x-panel>




    </div>

    @include('partials.session')


    <div class="card p-3 m-3">
        <div class="table-responsive-sm">
            <div class="d-flex justify-content-between">
                <h5 class="my-3">Posts</h5>
                <livewire:blog.posts-toggler></livewire:blog.posts-toggler>
            </div>

            <table class="table table-striped table-hover table-borderless align-middle">
                <thead class="table-dark rounded-top">
                    <tr>
                        <th>Image</th>
                        <th>Title <i class="bi bi-sort-alpha-down text-muted"></i></th>
                        <th>Status <i class="bi bi-funnel text-muted"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">

                    @forelse($posts as $post)
                    <tr class="table-dark">
                        <td scope="row"><img class="card-img-top" height="50" src="{{asset('storage' . $post->cover_image)}}" alt=""></td>
                        <td>{{$post->title}}</td>
                        <td>
                            <div class="status">
                                <span class="badge {{$post->status == 'public' ? 'bg-success' : 'bg-danger'}}">{{$post->status == 'public' ? 'Public' : 'draft'}}</span>
                            </div>
                        </td>
                        <td>
                            <div class="actions">

                                <a class="btn" href="{{route('posts.show', $post->slug)}}">
                                    <i class="bi bi-eyeglasses"></i>
                                </a>

                                <!-- /.edit button-->
                                <a class="edit btn btn-sm" href="{{route('admin.posts.edit', $post)}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                    </svg>
                                </a>


                                <!-- Delete button trigger modal -->
                                <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#delete-post-{{$post->id}}">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="delete-post-{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="modalTitlePost-{{$post->id}}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-fullscreen-lg-down" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalTitlePost-{{$post->id}}">Delete Post {{$post->title}}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                            </div>
                                            <div class="modal-body">
                                                {{__('Are you sure you want to delete the curent post? this action is irreversible')}}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{route('admin.posts.destroy', $post)}}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>


                    @empty
                    <tr class="table-dark">
                        <td scope="row">😑 No Posts yet!</td>

                    </tr>

                    @endforelse

                </tbody>

                <tfoot>
                    {{$posts->links('pagination::bootstrap-5')}}
                </tfoot>
            </table>
        </div>
    </div>



</div>
@endsection
