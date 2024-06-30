<div>


    <x-slot name="header">
        <div class="container">
            <h5>Settings</h5>
        </div>
    </x-slot>


    <div class="row">
        <div class="col-12 col-md-8">
            <h6>{{__('Contents Calendar')}}</h6>
            <div class="card mb-3">
                <livewire:blog.calendar></livewire:blog.calendar>

            </div>
            <!-- /Blog Calendar -->

            <h6>{{__('Blog Topics')}}</h6>
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
                                    @include('pacificdev::blog.partials.blog-settings')
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- /Blog Topics -->

        </div>
        <div class="col-12 col-md-4">
            <h6>{{__('Social Integrations')}}</h6>
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
            <!-- /Social Integration -->
            <h6>{{__('Images Optimizer')}}</h6>
            <div class="card mb-3 ">
                <div class="card-body">
                    <button class="btn btn-warning" wire:click="optimizeImages()">Run optimizer</button>
                    <span>{{$imagesOptimizationStatus}}</span>
                </div>
                <div class="card-footer">
                    <small>Run this once and it will optimize the images in background. Feedback is not in real time, images will keep processing after completation in bg -⚡ don't run this multiple times to avoid excessive server load</small>
                </div>
            </div>
            <!-- /Images Optimizer -->
        </div>
    </div>

</div>