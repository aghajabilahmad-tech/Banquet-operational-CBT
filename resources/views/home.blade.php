@extends('layouts.app')

@section('title', 'BEO System - Beranda Estetik')

@section('content')
    <main class="hero-section">
        <h1 class="hero-title">Wujudkan <strong>Event Perhotelan</strong> yang Berkesan</h1>

        @guest
            <div class="btn-group">
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk ke Sistem</a>
                <a href="{{ route('register') }}" class="btn btn-outline">Registrasi Baru</a>
            </div>
        @endguest
    </main>

    <div class="showcase-wrapper" id="showcaseWrapper">
        <!-- Kartu A -->
        <x-stacked-card
            pos="pos-1"
            title="Gala Dinner & Banquet"
            subtitle="Eksklusivitas dalam Skala Besar"
            image="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=1600"
        />

        <!-- Kartu B -->
        <x-stacked-card
            pos="pos-2"
            title="Corporate Meeting"
            subtitle="Ruang Rapat Profesional"
            image="https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?auto=format&fit=crop&q=80&w=1600"
        />

        <!-- Kartu C -->
        <x-stacked-card
            pos="pos-3"
            title="Wedding Ballroom"
            subtitle="Penataan Magis & Mewah"
            image="https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&q=80&w=1600"
        />
    </div>
@endsection

@push('scripts')
<script>
    const showcase = document.getElementById('showcaseWrapper');
    const cards = Array.from(document.querySelectorAll('.stacked-card'));

    let states = ['pos-1', 'pos-2', 'pos-3'];

    showcase.addEventListener('click', () => {
        states.push(states.shift());

        cards.forEach((card, index) => {
            card.classList.remove('pos-1', 'pos-2', 'pos-3');
            card.classList.add(states[index]);
        });
    });
</script>
@endpush
