<nav class="navbar">
    <div class="container-xxl">

        <div class="dropdown open">
            <button class="btn btn-white border-0 dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ __('Hi, ') . Auth::user()->name}}
            </button>
            <div class="dropdown-menu border-0 shadow" aria-labelledby="triggerId">
                <a wire:navigate class="dropdown-item" href="{{ url('/') }}">{{__('Blog')}}</a>
                <a wire:navigate class="dropdown-item" href="{{ url('admin') }}">{{__('Dashboard')}}</a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        <div class="ms-auto">


        </div>

        <!-- offcanvas Main App Menu -->
        <a class="nav-link d-flex flex-column align-items-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-layout-sidebar" viewBox="0 0 16 16">
                    <path d="M0 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3zm5-1v12h9a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H5zM4 2H2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h2V2z" />
                </svg>
            </span>
            <span class="d-none d-lg-inline-block fs_sm">Menu</span>
        </a>
        <div class="offcanvas offcanvas-bottom rounded-top-5 h-50" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-body d-flex flex-column justify-content-between">
                <!-- top Side Of Navbar -->
                @include('pacificdev::blog.partials.navigation')
            </div>
        </div>
        <!-- /offcanvas Main App Menu -->


    </div>
</nav>
