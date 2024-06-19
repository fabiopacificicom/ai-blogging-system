<!doctype html>
<!-- :data-bs-theme="darkMode ? 'dark' : 'light'" x-data="theme" -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head')

<body>

    <div id="blog">

        <header class="mb-0 bg-secondary-subtle border-bottom border-secondary-subtle">
            @include('partials.navbar')

            @if(isset($header))
            <div class="py-4">
                {{ $header }}
            </div>
            @endif

        </header>

        <div class="container-fluid">
            <div class="row flex-row-reverse">

                @include('partials.sidebar')
                <div class="col left-sidebar">
                    @yield('left-sidebar')
                </div>


                <main class="col-12 col-lg-8">
                    @yield('content')
                </main>

                <div class="col">
                    @yield('right-sidebar')
                </div>
            </div>
        </div>

    </div>

    @vite(['resources/js/blog/admin_blog.js', 'resources/js/blog/prism_blog.js'])

    @yield('beforeBodyEnd')
    @livewireScriptConfig

</body>

</html>
