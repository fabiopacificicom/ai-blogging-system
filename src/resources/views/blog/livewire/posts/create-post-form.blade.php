<div class="modal-content bg-black text-white">
    <div class="modal-body">

        <header class="d-flex justify-content-between flex-wrap">
            <div class="col-12 col-lg-8">
                <h2 class="py-2 text-muted">Generate Blog Post</h2>
                <p>Press generate for a complete draft ai generated</p>
            </div>
            <div class="py-2 filters d-flex align-items-start col-12 col-lg-4">
                <div class="mb-3">
                    <label for="" class="form-label">Model Name</label>
                    <input class="form-control" type="text" wire:model="model_name">
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
        </header>

        <div class="input-group mb-3">
            <textarea class="form-control" name="postPrompt" id="postPrompt" placeholder="Type here a draft of what you want to write or just a short description" wire:model="prompt"></textarea>

            <button class="btn btn-dark" type="button" @click="aiGenerate('title')">Draft</button>
        </div>

        <p>Customize your post image below</p>
        <div class="input-group mb-3">
            <textarea class="form-control" name="imagePrompt" id="imagePrompt" placeholder="describe the image you want for this blog post" wire:model="imagePrompt"></textarea>
            <button class="btn btn-dark" type="button" wire:click="generateImage()"> <i class="bi bi-image"></i></button>
        </div>
        @if($imagePath)
        <img width="200" src="{{asset('/storage' . $imagePath)}}" alt="">
        @endif

    </div>
    <div class="modal-footer border-0">
        <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-dark text-white" wire:click="publish()">
            Publish
            <i class="bi bi-stars"></i>
        </button>
    </div>
</div>