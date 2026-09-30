@php
    $links = [
        ['label' => 'Beranda',      'href' => route('home'), 'active' => request()->routeIs('home')],
        ['label' => 'Dashboard',    'href' => '#',           'active' => false],
        ['label' => 'Daftar Event', 'href' => '#',           'active' => false],
        ['label' => 'Logistik',     'href' => '#',           'active' => false],
    ];
@endphp

<header class="navbar">
    <a href="{{ route('home') }}" class="brand">BEO <span>System</span></a>

    <nav class="nav-links">
        @foreach ($links as $link)
            <a href="{{ $link['href'] }}" class="nav-link {{ $link['active'] ? 'active' : '' }}">
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>
</header>
