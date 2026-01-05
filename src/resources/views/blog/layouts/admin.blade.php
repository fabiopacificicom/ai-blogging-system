<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

@include('pacificdev::blog.partials.head')

<body>

    <div id="blog">

        <header class="mb-0 bg-secondary-subtle border-bottom border-secondary-subtle">
            @include('pacificdev::blog.partials.navbar')

            @if(isset($header))
            <div class="py-4">
                {{ $header }}
            </div>
            @endif

        </header>

        <div class="container-fluid">
            <div class="row flex-row-reverse">

                <div class="col left-sidebar">
                    @yield('left-sidebar')
                </div>


                <main class="col-10">
                    @yield('content')
                </main>

                <div class="col">
                    @yield('right-sidebar')
                </div>
            </div>
        </div>

    </div>

    @vite(['resources/js/vendor/pacificdev/blog-ai/admin.js'])

    @yield('beforeBodyEnd')
    @livewireScriptConfig

</body>

</html>