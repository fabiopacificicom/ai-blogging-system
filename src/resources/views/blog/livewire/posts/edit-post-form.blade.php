<div class="modal-content bg-black text-white">
    <div class="modal-body">

        <div class="metadata mt-2" x-data="{editTitle : false}">

            <i class="bi bi-pencil" x-bind:class="{ 'bi-x': editTitle }" x-on:click="editTitle = !editTitle" title="edit title"></i>
            <template x-if="editTitle">
                <input type="text" class="form-control" name="title" id="title" wire:model.blur="title">
            </template>
            <template x-if="!editTitle">
                <h4 class="text-muted">
                    {{$post->title}}
                    <span class="badge p-2 {{$post->status == 'public' ? 'bg-success' : 'bg-danger' }}" wire:click="togglePublish">
                        @if($post->status == 'draft')
                        <i class="bi bi-upload" title="publish"></i>
                        @else
                        <i class="bi bi-pencil-square" title="set to draft"></i>
                        @endif
                    </span>
                </h4>
            </template>
            @error('title')
            <div class="alert alert-danger" role="alert">
                <strong>Error</strong> {{$message}}
            </div>
            @enderror
            <h6>Slug: {{$post->slug}}</h6>
        </div>

        <div class="row g-2">
            <div class="col-12 col-lg-8 order-last order-lg-first">
                <h2 class="py-2 text-muted">Update Blog Post</h2>
                <p>Press regenarate to create recreate the article using the current content.</p>

                <div class="mb-3">
                    <textarea class="form-control" wire:model.live.debounce.1500ms="content" name="content" id="content" placeholder="Type here a draft of what you want to write or just a short description" rows="20"></textarea>
                </div>
                @error('content')
                <div class="alert alert-danger" role="alert">
                    <strong>Error</strong> {{$message}}
                </div>
                @enderror


                <div class="actions my-2 d-flex justify-content-between">
                    <button class="btn text-muted" type="button" wire:click="generateDraft" wire:loading.attr="disabled" wire:target="generateDraft">

                        <span class='' wire:loading.class.add="d-none" wire:target="generateDraft">
                            Regenerate
                            <i class="bi bi-stars"></i>
                        </span>
                        <span class="d-none" wire:loading.class.remove="d-none" wire:target="generateDraft">
                            <l-hourglass size="40" bg-opacity="0.1" speed="1.75" color="white"></l-hourglass>
                            <br>
                            {{__('wait')}}
                        </span>
                    </button>
                    @if($post->status == 'public')
                    <a class="btn btn-dark" href="{{route('posts.show', $post) }}">
                        View
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    @endif
                </div>
            </div>

            <div class="side col-12 col-lg-4 order-first order-lg-last">


                <div class="filters" x-data="{openFilters : false}">
                    <button class="btn" type="button" x-on:click="openFilters = !openFilters"><i class="bi bi-sliders"></i> Settings
                    </button>
                    <template x-if="openFilters">

                        <div>
                            <div class="mb-3">
                                <label for="" class="form-label">Model Name</label>
                                <input class="form-control" type="text" wire:model.trim="model_name">
                            </div>
                            @error('model_name')
                            <div class="alert alert-danger" role="alert">
                                <strong>Error</strong> {{$message}}
                            </div>
                            @enderror

                            <div class="m-1">
                                <label for="" class="form-label">Length</label>
                                <input type="number" min="2000" step="100" class="form-control" wire:model="max_tokens">
                            </div>
                            @error('max_tokens')
                            <div class="alert alert-danger" role="alert">
                                <strong>Error</strong> {{$message}}
                            </div>
                            @enderror

                            <div class="m-1">
                                <label for="" class="form-label">Creativity</label>
                                <small id="helpId" class="form-text text-muted">
                                    {{$temp}}

                                    @switch(true)

                                    @case($temp <= 0.7) <span class="badge bg-secondary">Normal</span>
                                        @break
                                        @case($temp > 0.8 && $temp <= 1.3) <span class="badge bg-success">Creative</span>
                                            @break
                                            @case($temp > 1.3 && $temp <= 1.7) <span class="badge bg-warning">Crazy</span>
                                                @break
                                                @case($temp >= 1.8)
                                                <span class="badge bg-danger">Drunk</span>
                                                @break

                                                @endswitch


                                </small>
                                <input class="form-range" type="range" name="temp" id="temp" aria-describedby="helpId" min="0" max="2" wire:model.live="temp" step="0.1" />
                            </div>
                            @error('temp')
                            <div class="alert alert-danger" role="alert">
                                <strong>Error</strong> {{$message}}
                            </div>
                            @enderror
                        </div>

                    </template>
                </div>


                @if($post->cover_image)
                <h5 class="text-muted">
                    Current Cover Image
                </h5>
                <img class="img-fluid" src="{{Storage::url($post->cover_image)}}" alt="">

                @endif
                <p>Regenerate cover image</p>
                <div class="input-group mb-3">
                    <textarea class="form-control" wire:model.live.debounce.1000ms="cover_image" name="cover_image" id="cover_image" placeholder="describe the image you want for this blog post" rows="3"></textarea>
                    <button class="btn btn-dark" type="button" wire:click="generateImage" wire:loading.attr="disabled" wire:target="generateImage">

                        <i class="bi bi-image" wire:loading.class.add="d-none" wire:target="generateImage"></i>
                        <span class="d-none" wire:loading.class.remove="d-none" wire:target="generateImage">
                            <l-helix size="45" speed="2.5" color="white"></l-helix>
                            <br>
                            {{__('wait')}}
                        </span>
                    </button>
                </div>
                @error('cover_image')
                <div class="alert alert-danger" role="alert">
                    <strong>Error</strong> {{$message}}
                </div>
                @enderror
                @if($imagePath != $post->cover_image)
                <img width="200" src="{{Storage::url($imagePath)}}" alt="">
                @endif


            </div>
        </div>

    </div>

</div>