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

            @if($post->status == 'public')
            <a class="btn btn-dark" href="{{route('posts.show', $post) }}">
                Show
            </a>
            @endif
        </div>

        @endif


        <div class="row g-2">
            <div class="col-12 col-lg-8 order-last order-lg-first">
                <h2 class="py-2 text-muted">Update Blog Post</h2>
                <p>Press draft to regenerate the article</p>

                <div class="input-group mb-3">
                    <textarea class="form-control" name="postPrompt" id="postPrompt" placeholder="Type here a draft of what you want to write or just a short description" wire:model="prompt" rows="10"></textarea>

                    <button class="btn btn-dark" type="button" wire:click="generateDraft" wire:loading.attr="disabled" wire:target="generateDraft">

                        <span class='' wire:loading.class.add="d-none" wire:target="generateDraft">Draft</span>
                        <span class="d-none" wire:loading.class.remove="d-none" wire:target="generateDraft">processig...</span>
                    </button>
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

                            <div class="m-1">
                                <label for="" class="form-label">Length</label>
                                <input type="number" min="2000" step="100" class="form-control" wire:model="max_tokens">
                            </div>

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
                                                @case($temp >= 1.8) <span class="badge bg-danger">Drunk</span>
                                                @break

                                                @endswitch


                                </small>
                                <input class="form-range" type="range" name="temp" id="temp" aria-describedby="helpId" min="0" max="2" wire:model.live="temp" step="0.1" />

                            </div>
                        </div>

                    </template>
                </div>


                @if($post->cover_image)
                <h5 class="text-muted">
                    Current Cover Image
                </h5>
                <img class="img-fluid" src="{{asset('/storage' . $post->cover_image)}}" alt="">

                @endif
                <p>Update your post image</p>
                <div class="input-group mb-3">
                    <textarea class="form-control" name="imagePrompt" id="imagePrompt" placeholder="describe the image you want for this blog post" wire:model="imagePrompt" rows="3"></textarea>
                    <button class="btn btn-dark" type="button" wire:click="generateImage" wire:loading.attr="disabled" wire:target="generateImage">

                        <i class="bi bi-image" wire:loading.class.add="d-none" wire:target="generateImage"></i>
                        <span class="d-none" wire:loading.class.remove="d-none" wire:target="generateImage">procesing...</span>
                    </button>
                </div>
                @if($imagePath)
                <img width="200" src="{{asset('/storage' . $imagePath)}}" alt="">
                @endif


            </div>
        </div>

    </div>

    <div class="modal-footer border-0">
        <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-dark text-white" wire:click="publish" wire:target="publish" wire:loadig.attr="disabled">
            Update
            <i class="bi bi-stars d-none" wire:target="publish" wire:loadig.class.remove="d-none"></i>
        </button>
    </div>
</div>