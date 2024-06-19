<!doctype html>
<!-- :data-bs-theme="darkMode ? 'dark' : 'light'" x-data="theme" -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Sweet Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <!-- Usando Vite -->


    @vite(['resources/scss/vendor/pacificdev/blog-ai/app.scss'])
    <link rel="stylesheet" href="{{asset('vendor/pacificdev/blog-ai/css/prism.css')}}">

    @stack('styles')
</head>

<body>
    <div id="app">

        <header>
            <nav class="navbar">
                <div class="container-fluid">
                    <a wire:navigate class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                        <i class="bi bi-robot"></i>
                        @guest
                        <span>{{ config('app.name', 'Fab-Ai') }}</span>
                        @else
                        <span class="fs_sm"> {{ __('Hi, ') . Auth::user()->name}}</span>
                        @endguest
                    </a>
                    <!-- darkmode toggler -->
                    {{--@include('partials.layout.theme-toggler')--}}
                    <!-- /darkmode toggler -->

                    @auth
                    <!-- offcanvas Main App Menu -->
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <i class="bi bi-layout-sidebar-inset-reverse"></i>
                    </button>
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body d-flex flex-column justify-content-between">
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav">
                                @guest
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{url('/') }}">{{ __('Home') }}</a>
                                </li>
                                @else
                                @if(Route::has('admin.dashboard'))
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.dashboard') }}">
                                        <span class="icon">
                                            <i class="bi bi-view-stacked"></i>
                                        </span>
                                        {{ __('Dashboard') }}
                                    </a>
                                </li>
                                @endif

                                @if(Route::has('admin.conversations.new'))

                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.conversations.new') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        {{ __('Conversation') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.conversations.index') }}">

                                        <i class="bi bi-chat-square-text"></i>
                                        {{ __('Conversations') }}
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.posts.index') }}">
                                        <i class="bi bi-file-earmark-richtext"></i>
                                        {{ __('Posts') }}
                                    </a>
                                </li>
                                @if(Route::has('admin.users.index'))

                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.users.index') }}">
                                        <i class="bi bi-people"></i>
                                        {{ __('Users') }}
                                    </a>
                                </li>
                                @endif
                                @endguest
                            </ul>

                            <!-- Right Side Of Navbar -->
                            <ul class="navbar-nav ml-auto border-top border-light">
                                <!-- Authentication Links -->
                                @guest

                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                                @endif
                                @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
                                                <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                                <path d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0h-7zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492V2.5z" />
                                            </svg>
                                        </span> {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('admin') }}">{{__('Dashboard')}}</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                                @endguest
                            </ul>
                        </div>
                    </div>
                    <!-- /offcanvas Main App Menu -->
                    @endauth
                </div>
            </nav>
        </header>
        @auth
        @endauth
        <main class="">
            @yield('content')
        </main>
        <!-- include('partials.footer') -->
    </div>


    @vite(['resources/js/vendor/pacificdev/blog-ai/app.js'])
    @stack('scripts')

    @livewireScriptConfig

</body>

</html>