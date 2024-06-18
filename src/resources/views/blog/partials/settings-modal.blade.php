<div class="modal fade" id="blog-settings-modal" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content bg-dark">
            <div class="modal-body" x-data="{
                title: {
                instructions: `{{ config('openai.presets.blog.title.instructions')}}`,
                prompt: `{{ config('openai.presets.blog.title.prompt')}}`,
                temperature:  `{{ config('openai.presets.blog.title.temperature')}}`,
                tokens:  `{{ config('openai.presets.blog.title.max_tokens')}}`
                },
                    summary: {
                prompt: `{{ config('openai.presets.blog.summary.prompt')}}`,
                temperature:  `{{ config('openai.presets.blog.summary.temperature')}}`,
                tokens:  `{{ config('openai.presets.blog.summary.max_tokens')}}`
                },
                    content: {
                prompt: `{{ config('openai.presets.blog.content.prompt')}}`,
                temperature:  `{{ config('openai.presets.blog.content.temperature')}}`,
                tokens:  `{{ config('openai.presets.blog.content.max_tokens')}}`
                }

            }">


                <div class="d-grid gap-2">
                    <a href="{{route('admin.linkedin.auth')}}" class="btn btn-primary">Connect to Linkedin</a>
                </div>

                <div class="blog-title-settings">
                    <h3 class="text-muted h4 mt-3">Title Generation Settings</h3>
                    <label>Accuracy</label>
                    <div class="mb-3 d-flex gap-1">
                        <input form="generate_post" type="range" name="title_temperature" class="form-range" min="0" max="2" step="0.1" x-model="title.temperature">
                        <span x-html="title.temperature"></span>
                    </div>
                    <label>Max Tokens</label>
                    <div class="mb-3 d-flex gap-1">
                        <input form="generate_post" type="range" name="title_max_tokens" class="form-range" min="0" max="30" step="1" x-model="title.tokens">
                        <span x-html="title.tokens"></span>
                    </div>
                    <label>Instructions</label>
                    <div class="mb-3 d-flex gap-1">
                        <textarea form="generate_post" class="form-control bg-secondary text-white-50" name="title_instructions" rows="4" x-model="title.instructions"></textarea>
                    </div>
                    <label>prompt</label>
                    <div class="mb-3 d-flex gap-1">
                        <textarea form="generate_post" class="form-control bg-secondary text-white-50" name="title_prompt" rows="4" x-model="title.prompt"></textarea>
                    </div>

                </div>
                <div class="blog-summary-settings">
                    <h3 class="text-muted h4 mt-3">Summary Generation Settings</h3>
                    <label>Accuracy</label>
                    <div class="mb-3 d-flex gap-1">
                        <input form="generate_post" type="range" name="summary_temperature" class="form-range" min="0" max="2" step="0.1" x-model="summary.temperature">
                        <span x-html="summary.temperature"></span>
                    </div>
                    <label>Max Tokens</label>
                    <div class="mb-3 d-flex gap-1">
                        <input form="generate_post" type="range" name="summary_max_tokens" class="form-range" min="0" max="200" step="1" x-model="summary.tokens">
                        <span x-html="summary.tokens"></span>
                    </div>
                    <label>Prompt</label>
                    <div class="mb-3 d-flex gap-1">
                        <textarea form="generate_post" name="summary_prompt" class="form-control bg-secondary text-white-50" rows="4" x-model="summary.prompt"></textarea>
                    </div>

                </div>
                <div class="blog-content-settings">
                    <h3 class="text-muted h4 mt-3">Content Generation Settings</h3>
                    <label>Accuracy</label>
                    <div class="mb-3 d-flex gap-1">
                        <input form="generate_post" type="range" name="content_temperature" class="form-range" min="0" max="2" step="0.1" x-model="content.temperature">
                        <span x-html="content.temperature"></span>
                    </div>
                    <label>Max Tokens</label>
                    <div class="mb-3 d-flex gap-1">
                        <input form="generate_post" type="range" name="content_max_tokens" class="form-range" min="0" max="3500" step="1" x-model="content.tokens">
                        <span x-html="content.tokens"></span>
                    </div>
                    <label>Prompt</label>
                    <div class="mb-3 d-flex gap-1">
                        <textarea form="generate_post" name="content_prompt" class="form-control bg-secondary text-white-50" rows="4" x-model="content.prompt"></textarea>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="generate_post" action="{{route('admin.posts.settings.store')}}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-dark text-white">Update Default Preset</button>
                </form>
            </div>
        </div>
    </div>
</div>