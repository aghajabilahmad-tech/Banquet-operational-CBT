@php
    $links = [
        ['label' => 'Beranda',      'href' => url('/'),       'active' => request()->is('/')],
        ['label' => 'Dashboard',    'href' => '#dashboard',   'active' => false],
        ['label' => 'Daftar Event', 'href' => '#event',       'active' => false],
        ['label' => 'Logistik',     'href' => '#logistik',    'active' => false],
    ];
@endphp

<header class="navbar">
    <a href="{{ url('/') }}" class="brand">BEO <span>System</span></a>

    <nav class="nav-links">
        @foreach ($links as $link)
            <a href="{{ $link['href'] }}" class="nav-link {{ $link['active'] ? 'active' : '' }}">
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>
</header>
