<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

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
    <link rel="stylesheet" href="{{asset('css/vendor/pacificdev/blog-ai/prism.css')}}">
    <script src="{{asset('js/vendor/pacificdev/blog-ai/prism.js')}}" defer></script>

    @yield('styles')
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
                                @if (Route::has('admin.dashboard'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('admin.dashboard') }}">
                                        <span class="icon">
                                            <i class="bi bi-view-stacked"></i>
                                        </span>
                                        {{ __('Dashboard') }}
                                    </a>
                                </li>
                                @endif

                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="{{route('admin.posts.index') }}">
                                        <i class="bi bi-file-earmark-richtext"></i>
                                        {{ __('Posts') }}
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
                                        <img width="30" class="rounded-circle" src="{{asset('storage' . Auth::user()->profile_image)}}" alt="">
                                        {{ Auth::user()->name }}
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

        <footer class="text-white-50 py-5">
            <div class="container">
                <p>
                    <strong>
                        FabAI &copy; Fabio Pacifici {{ now()->year }}
                    </strong>

                    Thanks for browsing the Fabulous Autonomous Blogging platform with AI - FabAI in short. All contents are humanly reviewed and occasionally written by myself.
                </p>
            </div>

        </footer>
        <!-- include('partials.footer') -->
    </div>


    @vite(['resources/js/vendor/pacificdev/blog-ai/app.js'])
    @stack('scripts')

    @livewireScriptConfig

</body>

</html>