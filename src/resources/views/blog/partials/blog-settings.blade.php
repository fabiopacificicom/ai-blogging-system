<form wire:submit.prevent="updateTopicsList">
    <div class="mb-3">
        <label for="topics">{{__('Topics')}}</label>
        <textarea class="form-control" name="topics" id="topics" cols="30" rows="10" wire:model="topics"></textarea>
        <small>List topics to use, one per line.</small>

    </div>

    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="random" wire:model="inRandomOrder">
            <label class="form-check-label" for="random">
                {{__('Follow in random order')}}
            </label>
        </div>

    </div>

    <button type="submit" class="btn btn-warning">{{__('Update Topics')}}</button>
</form>

<hr>
{{--


<!-- Blog Settings -->
<!-- Title fields: Istructions, prompt, model, temperature, max_tokens -->
<h6 class="my-3">{{__('Title Generation Settings')}}</h6>
<form id="blog_title" action="" method="" class="row row-cols-1 row-cols-lg-2" x-data="{
                        temperature: {{config('openai.presets.blog.title.temperature')}},
                        updateRange(event)
                        {
                        //event.target.form.submit()
                        }
                        }" x-on:input.prevent="updateRange($event)">
    <div class="col">
        <div class="mb-3">
            <label for="blog-title-instructions" class="form-label">Instructions</label>
            <textarea class="form-control" name="blog-title-instructions" id="blog-title-instructions" rows="3">{{config('openai.presets.blog.title.instructions')}}</textarea>
        </div>
    </div>
    <div class="col">
        <div class="mb-3">
            <label for="blog-title-prompt" class="form-label">Prompt</label>
            <textarea class="form-control" name="blog-title-prompt" id="blog-title-prompt" rows="2">{{config('openai.presets.blog.title.prompt')}}</textarea>
        </div>
    </div>
    <div class="col">
        <div class="mb-3">
            <label for="blog-temperature" class="form-label" title="the randomness level">Temperature</label>
            <div class="d-flex gap-1 justify-content-between">
                <span>0</span>
                <input type="range" class="form-range" min="0" max="2" step="0.1" id="blog-chat-temperature" name="temperature" value="0" x-model.debounce="temperature">
                <span x-text="temperature">2</span>
            </div>
        </div>
    </div>
    <div class="col">
        <label for="max_tokens">Max Tokens</label>
        <div class="mb-3">
            <input name="max_tokens" id="max_tokens" type="number" min="3500" max="4000" class="form-control" placeholder="4000(max_tokens)" aria-label="Button" value="{{config('openai.presets.blog.title.max_tokens')}}">
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-warning" disabled>Save</button>
    </div>
</form>
<hr>
<!-- Sumamry fields: Istructions, prompt, model, temperature, max_tokens -->
<h6 class="my-3">Summary fields</h6>
<form id="blog_summary" action="" method="" class="row" x-data="{
                        temperature: {{config('openai.presets.blog.summary.temperature')}},
                        updateRange(event)
                        {
                        //event.target.form.submit()
                        }
                        }" x-on:input.prevent="updateRange($event)">
    <div class="col-lg-6">
        <div class="mb-3">
            <label for="" class="form-label">Instructions</label>
            <textarea class="form-control" name="" id="" rows="q">{{config('openai.presets.blog.summary.instructions')}}</textarea>
        </div>
    </div>
    <div class="col-lg-6">

        <label for="chat_temperature" class="form-label" title="the randomness level">Temperature</label>
        <div class="d-flex gap-1 justify-content-between">
            <span>0</span>
            <input type="range" class="form-range" min="0" max="2" step="0.1" id="chat_temperature" name="temperature" x-model.debounce="temperature">
            <span x-text="temperature">2</span>
        </div>
    </div>
    <div class="col-lg-6">
        <label for="max_tokens">Max Tokens</label>
        <div class="mb-3">
            <input type="number" min="3500" max="4000" class="form-control" placeholder="4000(max_tokens)" aria-label="Button" value="{{config('openai.presets.blog.summary.max_tokens')}}">
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-warning" disabled>Save</button>
    </div>
</form>
<hr>
<!-- Content fields: Istructions, prompt, model, temperature, max_tokens -->
<h6 class="my-3">Content fields</h6>
<form id="blog_content" action="" method="" class="row" x-data="{
                        temperature: {{config('openai.presets.blog.content.temperature')}},
                        updateRange(event)
                        {
                        //event.target.form.submit()
                        }
                        }" x-on:input.prevent="updateRange($event)">
    <div class="col-lg-6">
        <div class="mb-3">
            <label for="" class="form-label">Pre-Prompt</label>
            <textarea class="form-control" name="" id="" rows="q">{{config('openai.presets.blog.content.prompt')}}</textarea>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-3">
            <label for="chat_temperature" class="form-label" title="the randomness level">Temperature</label>
            <div class="d-flex gap-1 justify-content-between">
                <span>0</span>
                <input type="range" class="form-range" min="0" max="2" step="0.1" id="chat_temperature" name="temperature" x-model.debounce="temperature">
                <span x-text="temperature">2</span>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <label for="max_tokens">Max Tokens</label>
        <div class="mb-3">
            <input type="number" min="3500" max="4000" class="form-control" placeholder="4000(max_tokens)" aria-label="Button" value="{{config('openai.presets.blog.content.max_tokens')}}">
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-warning" disabled>Save</button>
    </div>

</form>
<hr>
<!-- Image fields: prompt -->
<h6 class="my-3">Image fields</h6>
<form id="blog_image" action="" method="" class="row row-cols-1 row-cols-lg-2" x-data="{}">
    <div class="col">
        <div class="mb-3">
            <label for="" class="form-label">Pre-Prompt</label>
            <textarea class="form-control" name="" id="" rows="q">{{config('openai.presets.blog.content.prompt')}}</textarea>
        </div>
    </div>

    <div class="mb-3">
        <label for="type" class="form-label">Art type</label>
        <select class="form-select form-select-lg" name="type" id="type">
            @forelse(config('openai.presets.blog.image.type') as $type)
            <option value="{{$type}}">{{$type}}</option>
            @empty
            <option value="">No preset available</option>
            @endforelse
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-warning" disabled>Save</button>
    </div>
</form>

--}}
