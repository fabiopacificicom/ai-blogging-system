<ul class="navbar-nav">
    @guest
    <li class="nav-item">
        <a wire:navigate class="nav-link" href="{{url('/') }}">{{ __('Home') }}</a>
    </li>
    @else
    <li class="nav-item">
        <a wire:navigate class="nav-link" href="{{route('admin.dashboard') }}">
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-view-stacked" viewBox="0 0 16 16">
                    <path d="M3 0h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3zm0 8h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1H3z" />
                </svg>
            </span>
            <span x-bind:class="{ 'd-none': !open }">
                {{ __('Dashboard') }}
            </span>
        </a>
    </li>

    <li class="nav-item">
        <a wire:navigate class="nav-link" href="{{route('admin.posts.index') }}">
            @include('partials.icons.blog')
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