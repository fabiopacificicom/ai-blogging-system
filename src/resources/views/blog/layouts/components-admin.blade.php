<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- :data-bs-theme="darkMode ? 'dark' : 'light'" x-data="theme" -->
@include('pacificdev::blog.partials.head')

<body>

    <div id="blog">

        <header class="mb-0 bg-secondary-subtle border-bottom border-secondary-subtle">
            @include('pacificdev::blog.partials.navbar')

            <!-- Page Heading -->
            @if(isset($header))
            <div class="py-4 bg-body-tertiary">
                {{ $header }}
            </div>
            @endif
        </header>

        <div class="container">
            <div class="row">
                @include('pacificdev::blog.partials.sidebar')

                <main class="col col-lg-9">
                    {{$slot}}
                </main>
            </div>
        </div>

    </div>
    @livewireScriptConfig

</body>

</html>
