<div class="modal-content bg-black text-white">
    <div class="modal-body">
        @include('pacificdev::blog.partials.session')
        @include('pacificdev::blog.partials.validation')

        @if($post)

        <div class="metadata mt-2">
            <h4 class="text-muted">
                Title:{{$post->title}}
                <span class="badge bg-primary">{{$post->status}}</span>
            </h4>
            <h6>Slug: {{$post->slug}}</h6>
        </div>
        <div class="actions">
            <a class="btn btn-dark" href="{{ route('admin.posts.edit', $post) }}">
                Edit
            </a>

            @if($post->status == 'public')
            <a class="btn btn-dark" href="{{route('posts.show', $post) }}">
                Show
            </a>
            @endif
        </div>

        @endif

        <div class="row g-2">
            <div class="col-12 col-lg-8 order-last order-lg-first">
                <div class="card bg-radial-dark">
                    <div class="card-body">
                        <h2 class="py-2 text-muted">Generate Blog Post</h2>
                        <p>Press generate for a complete draft ai generated</p>

                        <div class="input-group mb-3">
                            <textarea class="form-control" name="content" id="content" placeholder="Type here a draft of what you want to write or just a short description" wire:model="content" rows="10"></textarea>

                            <button class="btn btn-dark" type="button" wire:click="generateDraft" wire:loading.attr="disabled" wire:target="generateDraft">

                                <span class='' wire:loading.class.add="d-none" wire:target="generateDraft">Draft</span>
                                <span class="d-none" wire:loading.class.remove="d-none" wire:target="generateDraft">
                                    <l-hourglass size="40" bg-opacity="0.1" speed="1.75" color="white"></l-hourglass>
                                    <br>
                                    {{__('wait')}}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="side col-12 col-lg-4 order-first order-lg-last">
                <div class="card bg-radial-dark">

                    <div class="filters">
                        <div class="mb-3">
                            <label for="model_name" class="form-label">Model Name</label>
                            <input id="model_name" class="form-control" type="text" wire:model.trim="model_name">
                        </div>

                        <div class="m-1">
                            <label for="max_tokens" class="form-label">Length</label>
                            <input id="max_tokens" type="number" min="2000" step="100" class="form-control" wire:model="max_tokens">
                        </div>
                        <div class="m-1">
                            <label for="temp" class="form-label">Creativity</label>
                            <small id="helpId" class="form-text text-muted">
                                {{$temp}}

                                @switch(true)

                                @case($temp <= 0.7) <span class="badge bg-secondary">Normal</span>
                                    @break
                                    @case($temp > 0.8 && $temp <= 1.3) <span class="badge bg-success">Creative</span>
                                        @break
                                        @case($temp > 1.3 && $temp <= 1.7) <span class="badge bg-warning">Crazy</span>
                                            @break
                                            @case($temp >= 1.8) <span class="badge bg-danger">Drunk</span>
                                            @break

                                            @endswitch


                            </small>
                            <input class="form-range" type="range" name="temp" id="temp" aria-describedby="helpId" min="0" max="2" wire:model.live="temp" step="0.1" />

                        </div>
                    </div>

                    <p>Customize your post image below</p>
                    <div class="input-group mb-3">
                        <textarea class="form-control" name="cover_image" id="cover_image" placeholder="describe the image you want for this blog post" wire:model="cover_image" rows="3"></textarea>
                        <button class="btn btn-dark" type="button" wire:click="generateImage" wire:loading.attr="disabled" wire:target="generateImage">

                            <i class="bi bi-image" wire:loading.class.add="d-none" wire:target="generateImage"></i>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="generateImage">
                                <l-helix size="45" speed="2.5" color="white"></l-helix>
                                <br>
                                {{__('wait')}}
                            </span>
                        </button>
                    </div>
                    @if($imagePath)
                    <img width="200" src="{{asset('/storage' . $imagePath)}}" alt="">
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="modal-footer border-0">
        @if(Route::currentRouteName() === 'admin.posts.create')
        <a type="button" class="btn" href="{{route('admin.posts.index')}}">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
        @else
        <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
        @endif
        <button type="submit" class="btn btn-dark text-white" wire:click="publish()" wire:target="publish" wire:loadig.attr="disabled">
            Publish
            <i class="bi bi-stars d-none" wire:target="publish" wire:loadig.class.remove="d-none"></i>
        </button>
    </div>
</div>