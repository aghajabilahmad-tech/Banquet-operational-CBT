@php
    $links = [
        ['label' => 'Dashboard',    'href' => url('/'),               'active' => request()->is('/')],
        ['label' => 'Buat Event',   'href' => route('events.create'), 'active' => request()->routeIs('events.create'), 'admin_only' => true],
        ['label' => 'Daftar Event', 'href' => route('events.index'),  'active' => request()->routeIs('events.index') || (request()->routeIs('events.*') && !request()->routeIs('events.create'))],
        ['label' => 'Logistik',     'href' => '#logistik',            'active' => false],
    ];
@endphp

<header class="navbar">
    <a href="{{ url('/') }}" class="brand">BEO <span>System</span></a>

    <nav class="nav-links">
        @foreach ($links as $link)
            @if(empty($link['admin_only']) || (auth()->check() && auth()->user()->isAdmin()))
                <a href="{{ $link['href'] }}" class="nav-link {{ $link['active'] ? 'active' : '' }}">
                    {{ $link['label'] }}
                </a>
            @endif
        @endforeach

        @auth
            <span class="nav-link" style="color: #1E40AF; font-weight: 700;">
                {{ auth()->user()->full_name }} ({{ ucfirst(auth()->user()->role) }})
            </span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer; font-size: 0.95rem; font-weight: 600; color: #EF4444;">
                    Keluar
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link {{ request()->is('login') || request()->is('register') ? 'active' : '' }}" style="color: #1E40AF; font-weight: 700;">
                Masuk
            </a>
        @endauth
    </nav>
</header>
