<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    <!-- Usando Vite -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    @vite(['resources/scss/blog/admin_blog.scss', 'resources/scss/blog/prism_blog.scss'])
    @yield('styles')
</head>
