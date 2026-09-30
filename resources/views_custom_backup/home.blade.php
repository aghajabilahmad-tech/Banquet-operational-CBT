@extends('layouts.app')

@section('title', 'BEO System - Beranda Estetik')

@section('content')
    <main class="hero-section">
        <h1 class="hero-title">Wujudkan <strong>Event Perhotelan</strong> yang Berkesan</h1>

        <div class="btn-group">
            <a href="#" class="btn btn-primary">Mulai Sekarang</a>
            <a href="#" class="btn btn-outline">Lihat Jadwal</a>
        </div>
    </main>

    <div class="showcase-wrapper" id="showcaseWrapper">
        <x-stacked-card
            pos="pos-1"
            title="Gala Dinner & Banquet"
            subtitle="Eksklusivitas dalam Skala Besar"
            image="images/gala-dinner.jpg" />

        <x-stacked-card
            pos="pos-2"
            title="Corporate Meeting"
            subtitle="Ruang Rapat Profesional"
            image="images/corporate-meeting.jpg" />

        <x-stacked-card
            pos="pos-3"
            title="Wedding Ballroom"
            subtitle="Penataan Magis & Mewah"
            image="images/wedding-ballroom.jpg" />
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
