<div>


    <x-slot name="header">
        <div class="container">
            <h5>Settings</h5>
        </div>
    </x-slot>

    <h6 class="my-4">{{__('Social Integrations')}}</h6>

    <div class="card my-4">
        <div class="card-body">
            <a href="{{route('admin.linkedin.auth')}}" class="btn btn-primary">Connect to Linkedin</a>
        </div>
        <div class="card-footer">

            <small>
                Click to authenticate with linkedin and connect your blog so it can share your articles autonomously.
            </small>
        </div>
    </div>

    <h6>{{__('Blog Management')}}</h6>

    <div class="card mb-3">
        <div class="card-body">
            <div class="accordion accordion-flush" id="ai-blog">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-blog" aria-expanded="true" aria-controls="flush-blog">
                            {{ __('AI configuration') }}
                        </button>
                    </h2>
                    <div id="flush-blog" class="accordion-collapse" aria-labelledby="flush-headingOne" data-bs-parent="#ai-settings">
                        <div class="accordion-body">
                            @include('blog.partials.blog-settings')
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>