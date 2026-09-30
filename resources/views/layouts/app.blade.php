<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BEO System - Beranda Estetik')</title>

    <link rel="stylesheet" href="{{ asset('css/beo.css') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @stack('styles')
</head>
<body>

    <div class="ambient-glow"></div>

    <x-navbar />

    @yield('content')

    @stack('scripts')
</body>
</html>
