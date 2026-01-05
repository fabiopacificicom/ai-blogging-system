<div>


    <x-slot name="header">
        <div class="container">
            <h5>Settings</h5>
        </div>
    </x-slot>


    <div class="row">
        <div class="col-12 col-md-8 col-lg-9">
            <h6 class="mt-3">{{__('Contents Calendar')}}</h6>
            <div class="card">
                <div class="card-body">
                    <p class="lead">
                        Select your contents calendar and sharing settings below.
                        Automated posts are created based on the post generation scheduler and shared based on the post share scheduler.
                    </p>
                    <livewire:blog.calendar></livewire:blog.calendar>
                </div>

            </div>
            <!-- /Blog Calendar -->

            <h6 class="mt-3">{{__('Blog Topics')}}</h6>
            <div class="card mb-3">
                <div class="card-body">
                    <p class="lead">Add a list of topic one by line comma separated. One topic is picked randomly and used to autogenerate a blog post based on the above schedule</p>
                    @include('pacificdev::blog.partials.blog-settings')
                </div>
            </div>
            <!-- /Blog Topics -->

        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <h6 class="mt-3">{{__('Social Integrations')}}</h6>
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
            <h6 class="mt-3">{{__('Images Optimizer')}}
                <span class="badge bg-warning text-dark">Experimental</span>
            </h6>
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