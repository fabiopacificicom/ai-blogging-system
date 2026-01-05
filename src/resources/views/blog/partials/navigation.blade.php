<ul class="navbar-nav">
    @guest
    <li class="nav-item">
        <a class="nav-link" href="{{url('/') }}">{{ __('Home') }}</a>
    </li>
    @else
    <li class="nav-item">
        @if(Route::has('admin.dashboard'))
        <a class="nav-link" href="{{route('admin.dashboard') }}">
            <span class="icon">
                <i class="bi bi-view-stacked"></i>
            </span>
            <span x-bind:class="{ 'd-none': !open }">
                {{ __('Dashboard') }}
            </span>
        </a>
       
        @endif
    </li>

    <li class="nav-item">
        <a wire:navigate class="nav-link" href="{{route('admin.posts.index') }}">
            <i class="bi bi-file-earmark-richtext"></i>
            <span x-bind:class="{ 'd-none': !open }">
                {{ __('Blog') }}
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a wire:navigate class="nav-link" href="{{route('admin.blog.settings') }}">
            <i class="bi bi-sliders2-vertical"></i>
            <span x-bind:class="{ 'd-none': !open }">
                {{ __('Settings') }}
            </span>
        </a>
    </li>
    @endguest
</ul>