<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BEO System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>

    <div class="ambient-glow"></div>

    <x-navbar />

    @yield('content')

    {{-- Aktifkan kalau halamannya butuh footer (lihat catatan di bawah) --}}
    {{-- <x-footer /> --}}

    @stack('scripts')
</body>
</html>
