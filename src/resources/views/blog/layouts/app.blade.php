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

    @vite(['resources/scss/blog/app.scss', 'resources/scss/blog/prism_blog.scss'])

    @yield('styles')
</head>

<body>
    <div id="app">

        <header>
            <nav class="navbar">
                <div class="container-fluid">
                    <a wire:navigate class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                        <svg xmlns=" http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-robot" viewBox="0 0 16 16">
                            <path d="M6 12.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5ZM3 8.062C3 6.76 4.235 5.765 5.53 5.886a26.58 26.58 0 0 0 4.94 0C11.765 5.765 13 6.76 13 8.062v1.157a.933.933 0 0 1-.765.935c-.845.147-2.34.346-4.235.346-1.895 0-3.39-.2-4.235-.346A.933.933 0 0 1 3 9.219V8.062Zm4.542-.827a.25.25 0 0 0-.217.068l-.92.9a24.767 24.767 0 0 1-1.871-.183.25.25 0 0 0-.068.495c.55.076 1.232.149 2.02.193a.25.25 0 0 0 .189-.071l.754-.736.847 1.71a.25.25 0 0 0 .404.062l.932-.97a25.286 25.286 0 0 0 1.922-.188.25.25 0 0 0-.068-.495c-.538.074-1.207.145-1.98.189a.25.25 0 0 0-.166.076l-.754.785-.842-1.7a.25.25 0 0 0-.182-.135Z" />
                            <path d="M8.5 1.866a1 1 0 1 0-1 0V3h-2A4.5 4.5 0 0 0 1 7.5V8a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1v1a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1v-.5A4.5 4.5 0 0 0 10.5 3h-2V1.866ZM14 7.5V13a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7.5A3.5 3.5 0 0 1 5.5 4h5A3.5 3.5 0 0 1 14 7.5Z" />
                        </svg>
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
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.dashboard') }}">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-view-stacked" viewBox="0 0 16 16">
                                                <path d="M3 0h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3zm0 8h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1H3z" />
                                            </svg>
                                        </span>
                                        {{ __('Dashboard') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.conversations.new') }}">
                                        @include('partials.icons.plus')
                                        {{ __('Conversation') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.conversations.index') }}">

                                        @include('partials.icons.list')
                                        {{ __('Conversations') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.posts.index') }}">
                                        @include('partials.icons.blog')
                                        {{ __('Posts') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.users.index') }}">
                                        @include('partials.icons.people')
                                        {{ __('Users') }}
                                    </a>
                                </li>
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

    @vite(['resources/js/blog/app_blog.js', 'resources/js/blog/prism_blog.js'])
    @livewireScriptConfig

</body>

</html>
