<div>
    @php
    use PacificDev\BlogAi\Models\Post;
    @endphp
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row">
                <button class="btn" data-bs-toggle="modal" data-bs-target="#create-post-modal">
                    <i class="bi bi-body-text"></i>
                    Generate
                </button>
                @include('pacificdev::blog.partials.create-modal')
            </div>
            <div class="row row-cols-2 row-cols-lg-4 g-1">

                <x-pacificdev-panel class="col p-2" route="#" title="TotalWords" value="">
                    <x-slot name="icon">
                        <i class="bi bi-card-text"></i>
                    </x-slot>
                    <h3>
                        <livewire:blog.posts-words-counter></livewire:blog.posts-words-counter>
                    </h3>
                </x-pacificdev-panel>


                <x-pacificdev-panel class="col p-2" route="{{route('admin.posts.index')}}" title="{{__('Short Posts')}}" value="{{ Post::count()}}">
                    <x-slot name="icon">
                        <i class="bi bi-markdown"></i>
                    </x-slot>
                </x-pacificdev-panel>


                <x-pacificdev-panel class="col p-2" route="{{route('admin.posts.create')}}" title="You write" value="Write">
                    <x-slot name="icon">
                        <i class="bi bi-plus-circle-fill"></i>
                    </x-slot>
                </x-pacificdev-panel>

                <x-pacificdev-panel class="col p-2" route="{{route('admin.blog.settings')}}" title="Blog Settings" value="Customize">
                    <x-slot name="icon">
                        <i class="bi bi-sliders2-vertical"></i>
                    </x-slot>
                </x-pacificdev-panel>

            </div>
        </div>
    </x-slot>

    @include('pacificdev::blog.partials.session')

    <!-- Options -->
    <div class="d-flex align-items-center my-3 position-relative">
        <h5>{{__('Posts')}}</h5>
        <div class="ms-auto d-flex">
            <div class="d-none d-md-block">
                <livewire:blog.posts-toggler :key="'toggle-posts-visibility'" />
            </div>
            <livewire:blog.search-posts :key="'search-posts-box'" />
        </div>
    </div>
    <!-- Posts Table -->
    <div class="table-responsive-sm py-1 my-3 bg-dark rounded">
        <table class="table table-striped table-hover align-middle table-borderless" class="bg-secondary-subtle">
            <thead class="border-bottom border-secondary-subtle">
                <tr class="fs_sm">
                    <th class="d-none d-sm-table-cell">Image</th>
                    <th class="w-75">Title
                        <i class="bi bi-sort-alpha-down text-muted"></i>
                    </th>
                    <th class="d-none d-sm-table-cell">
                        <span class="d-flex"><span>Author</span> <i class="bi bi-funnel text-muted"></i></span>
                    </th>
                    <th>
                        <span class="d-flex"><span>Status</span> <i class="bi bi-funnel text-muted"></i></span>
                    </th>
                    <th>
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="table-group-divider">

                @forelse($posts as $post)
                <tr :key="$post->id">
                    <td scope="row" class="d-none d-sm-table-cell">
                        <img class="img-fluid object-fit-cover" width="140" src="{{asset('storage' . $post->cover_image)}}" alt="">
                    </td>
                    <td>
                        {{$post->title}}
                        <a class="d-block text-decoration-none h-100 text-muted" href="{{ $post->status == 'draft' ? '#' : route('posts.show', $post)}}" wire:navigate>
                        </a>
                    </td>
                    <td class="d-none d-sm-table-cell">{{$post->author}}</td>
                    <td>

                        <livewire:blog.post-status-toggler :$post :key="'toggle-post-' . $post->id" />

                    </td>
                    <td>
                        <div class="actions">

                            <div class="dropdown">
                                <button class="btn" type="button" id="actionsTriggerPost{{$post->id}}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="actionsTriggerPost{{$post->id}}">
                                    <a class="dropdown-item" wire:click.prevent="sharePost($post)">
                                        <i class="bi bi-share"></i> Share
                                    </a>
                                    <a class="dropdown-item" href="{{$post->status == 'draft' ? '#' : route('posts.show', $post)}}" wire:navigate>
                                        <i class="bi bi-eyeglasses"></i> View
                                    </a>
                                    <a class="edit dropdown-item" href="{{route('admin.posts.edit', $post)}}" wire:navigate>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <h6 class="dropdown-header">Attention</h6>

                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#delete-post-{{$post->id}}">
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </a>
                                </div>
                            </div>

                            <!-- Delete Modal -->
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
                    <td scope="row" colspan="5">😑 No Posts yet!</td>

                </tr>

                @endforelse

            </tbody>
            <tfoot class="border-top border-secondary-subtle">
                <tr class="fs_sm">
                    <th class="d-none d-sm-table-cell">Image</th>
                    <th class="w-75">Title
                        <i class="bi bi-sort-alpha-down text-muted"></i>
                    </th>
                    <th class="d-none d-sm-table-cell">
                        <span class="d-flex"><span>Author</span> <i class="bi bi-funnel text-muted"></i></span>
                    </th>
                    <th>
                        <span class="d-flex"><span>Status</span> <i class="bi bi-funnel text-muted"></i></span>
                    </th>
                    <th>
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </tfoot>
        </table>
        <div>
            {{$posts->links('pagination::bootstrap-5')}}
        </div>

    </div>

</div>