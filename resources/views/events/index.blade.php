@extends('layouts.app')

@section('title', 'BEO System - Daftar Event')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
@endpush

@section('content')
@if(session('success'))
    <div class="alert-toast-container" id="toast-success">
        <div class="alert-toast">
            <div class="alert-toast-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="alert-toast-body">
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
            <button type="button" class="alert-toast-close" onclick="document.getElementById('toast-success').remove()">&times;</button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000);
    </script>
@endif

<div class="main-container">
    <!-- LEFT PANEL: HERO BANNER DINAMIS -->
    <div class="left-panel">
        @php
            $firstEvent = $events->first();
        @endphp
        <img id="hero-img" class="left-panel-bg" 
             src="{{ $firstEvent?->image_url ?? 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80' }}" 
             alt="Event Background">
        
        <div class="left-panel-content">
            <div class="status-dot-wrapper" id="hero-status" style="color: {{ $firstEvent?->status_color ?? '#60A5FA' }};">
                <div class="status-dot" id="hero-dot" style="background-color: {{ $firstEvent?->status_color ?? '#60A5FA' }}; box-shadow: 0 0 10px {{ $firstEvent?->status_color ?? '#60A5FA' }};"></div>
                <span>{{ $firstEvent?->status_label ?? 'Upcoming' }}</span>
            </div>
            
            <h1 id="hero-title">{!! $firstEvent?->hero_title_formatted ?? '<strong>Daftar</strong><br>Event' !!}</h1>
            
            <p class="event-meta" id="hero-meta">
                @if($firstEvent)
                    Target: {{ $firstEvent->estimated_guest_count }} Pax &mdash; {{ $firstEvent->roomLayout?->layout_name ?? 'Standard' }}<br>
                    {{ $firstEvent->formatted_date }}
                @else
                    Belum ada event tersedia saat ini.<br>-
                @endif
            </p>
            
            <a href="{{ $firstEvent ? route('events.show', $firstEvent->id) : '#' }}" id="hero-action-btn" class="btn-action">
                Lihat Detail BEO
            </a>
        </div>
    </div>

    <!-- RIGHT PANEL: SEARCH, FILTER, CALENDAR, SLIDER, STATS -->
    <div class="right-panel">
        <div class="top-section">
            <div class="search-filter-col">
                <div class="search-box-wrapper">
                    <input type="text" id="search-input" class="search-box" placeholder="Cari nama instansi atau acara..." autocomplete="off">
                    <button type="button" id="search-clear-btn" class="search-clear-btn" title="Hapus pencarian">&times;</button>
                </div>
                
                <div class="filter-group">
                    <button type="button" class="filter-btn active" data-status="all">Semua</button>
                    <button type="button" class="filter-btn" data-status="aktif">Aktif</button>
                    <button type="button" class="filter-btn" data-status="upcoming">Upcoming</button>
                    <button type="button" class="filter-btn" data-status="ongoing">Ongoing</button>
                    <button type="button" class="filter-btn" data-status="completed">Selesai</button>
                    <button type="button" class="filter-btn" data-status="canceled">Dibatalkan</button>
                </div>
            </div>

            <!-- WIDGET KALENDER -->
            <div class="calendar-widget">
                <div class="cal-header">
                    <span class="cal-nav-btn" id="cal-prev-btn" title="Bulan Sebelumnya">&#10094;</span>
                    <div class="cal-month-title" id="cal-month-title">September 2026</div>
                    <span class="cal-nav-btn" id="cal-next-btn" title="Bulan Berikutnya">&#10095;</span>
                </div>
                <div class="cal-grid">
                    <div class="day">Sen</div><div class="day">Sel</div><div class="day">Rab</div><div class="day">Kam</div><div class="day">Jum</div><div class="day">Sab</div><div class="day">Min</div>
                </div>
                <div class="cal-grid" id="cal-grid-dates">
                    <!-- Tanggal digenerate dinamis oleh events.js -->
                </div>
            </div>
        </div>
        
        <!-- CARD SLIDER -->
        <div class="slider-wrapper">
            <div class="card-slider" id="slider">
                @forelse ($events as $index => $event)
                    <div class="event-card {{ $index === 0 ? 'active' : '' }}" 
                         onclick="focusCard(this)" 
                         data-id="{{ $event->id }}"
                         data-code="{{ $event->event_code }}"
                         data-title="{!! htmlspecialchars($event->hero_title_formatted) !!}" 
                         data-date="{{ $event->formatted_date }}" 
                         data-raw-date="{{ $event->event_date?->format('Y-m-d') }}"
                         data-guests="{{ $event->estimated_guest_count }} Pax" 
                         data-layout="{{ $event->roomLayout?->layout_name ?? 'Standard' }}" 
                         data-status="{{ $event->status_label }}" 
                         data-color="{{ $event->status_color }}" 
                         data-border="{{ $event->status_border }}" 
                         data-bg="{{ $event->status_bg }}"
                         data-image="{{ $event->image_url }}"
                         data-detail-url="{{ route('events.show', $event->id) }}"
                         data-search-term="{{ strtolower($event->event_name . ' ' . ($event->institution?->institution_name ?? '') . ' ' . ($event->roomLayout?->layout_name ?? '') . ' ' . $event->notes) }}">
                        
                        <div class="card-header">
                            <div class="card-badge" style="border: 1px solid {{ $event->status_border }}; color: {{ $event->status_text_color }}; background: {{ $event->status_bg }};">
                                {{ $event->status_label }}
                            </div>
                        </div>

                        <div class="card-body">
                            <h3>{{ $event->event_name }}</h3>
                            <p class="subtitle">{{ $event->institution?->institution_name ?? 'Banquet Order' }}</p>
                            <p class="desc">{{ $event->notes ?? 'Tidak ada catatan tambahan untuk event ini.' }}</p>
                        </div>

                        <div class="card-footer">
                            <div class="footer-item">
                                <span class="footer-label">Tanggal</span>
                                <span class="footer-value">{{ $event->formatted_date }}</span>
                            </div>
                            <div class="footer-item">
                                <span class="footer-label">Tamu</span>
                                <span class="footer-value">{{ $event->estimated_guest_count }} Pax</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-events-placeholder" style="display: flex;">
                        <div class="no-events-icon">&#128197;</div>
                        <div class="no-events-title">Belum Ada Event</div>
                        <p class="no-events-desc">Data event di database masih kosong.</p>
                    </div>
                @endforelse

                <div style="min-width: 200px;"></div>
            </div>

            <!-- Pesan jika hasil pencarian / filter kosong -->
            <div id="no-events-notice" class="no-events-placeholder" style="display: none;">
                <div class="no-events-icon">&#128197;</div>
                <div class="no-events-title">Event Tidak Ditemukan</div>
                <p class="no-events-desc">Tidak ada jadwal event yang sesuai dengan kata kunci atau filter tanggal yang dipilih.</p>
                <button type="button" class="btn-action" style="margin-top: 15px; font-size: 0.85rem; padding: 10px 24px;" onclick="resetAllFilters()">
                    Reset Filter
                </button>
            </div>
        </div>

        <!-- STATISTIK DAN FOOTER -->
        <div class="bottom-row">
            <div class="stats-container">
                <div class="stat-box">
                    <span class="stat-label">Total Event</span>
                    <span class="stat-value" id="stat-total">{{ $totalEvents }}</span>
                    <span class="stat-trend">&#8593; {{ abs($totalTrendDiff) }} dari bulan lalu</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">Event Aktif</span>
                    <span class="stat-value" id="stat-active">{{ $activeEventsCount }}</span>
                    <span class="stat-trend">&#8593; Aktif & Mendatang</span>
                </div>
            </div>

            <div class="right-footer-links">
                <div>&copy; {{ date('Y') }} BEO System. All rights reserved.</div>
                <div class="footer-nav">
                    <a href="#">Bantuan</a>
                    <a href="#">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/events.js') }}"></script>
@endpush
