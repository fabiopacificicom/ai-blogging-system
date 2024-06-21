<!-- Modal Body-->
<div class="modal fade" id="create-post-modal" tabindex="-1" role="dialog" aria-labelledby="createPostModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content bg-dark  text-white" x-data="postgen">
            <div class="modal-body">

                <div class="input-group mb-3">
                    <input form="generate_post" type="text" name="title" id="title" class="form-control bg-dark text-white" placeholder="Generated title will be shown here" aria-describedby="helpId" x-model="title_prompt">

                    <button class="btn btn-outline-light" type="button" @click="aiGenerate('title')">Generate Title</button>
                </div>

                <div class="input-group mb-3">
                    <input form="generate_post" type="text" name="image" id="image" class="form-control bg-dark text-white" placeholder="Write a descriptive text to generate an image" aria-describedby="helpId" x-model="prompt_image">
                    <input form="generate_post" type="text" name="cover_image" :value="cover_image_path" hidden>
                    <button class="btn btn-outline-light" type="button" @click="aiGenerate('image')">Generate Image</button>
                </div>
                <template x-if="cover_image_path">
                    <img width="200" :src="'/storage/' + cover_image_path" alt="">
                </template>

                <div class="input-group mb-3">
                    <textarea form="generate_post" class="form-control bg-dark  text-white" name="summary" id="summary" rows="5" x-model="prompt_summary" placeholder="Generate a title before you can generate a summary"></textarea>
                    <button class="btn btn-outline-light" type="button" @click="aiGenerate('summary', title_prompt) " x-show="title_prompt">Generate Summary</button>
                </div>

                <div class="input-group mb-3">
                    <textarea form="generate_post" class="form-control bg-dark text-white" name="content" id="content" rows="5" x-model="prompt_content"></textarea>
                    <button class="btn btn-outline-light" type="button" @click="aiGenerate('content', title_prompt, prompt_summary)" x-show="prompt_summary">Generate Content</button>
                </div>

            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="generate_post" action="{{route('admin.posts.store')}}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-dark text-white">Generate</button>
                </form>
            </div>
        </div>
    </div>
</div>
