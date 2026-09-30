@extends('layouts.app')

@section('title', 'BEO System - Buat BEO Baru')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/create-event.css') }}">
@endpush

@section('content')
<div class="create-event-wrapper">

    <!-- HEADER HALAMAN -->
    <header class="create-event-header">
        <h1 class="page-title">Buat BEO <span class="title-light">Baru</span></h1>
        <p class="page-subtitle">Isi formulir di bawah ini untuk merancang Banquet Event Order ke dalam sistem.</p>
    </header>

    <!-- GRID DUA KOLOM (FORM CARD & PANEL DEKORATIF NAVY) -->
    <div class="create-event-grid">

        <!-- KOLOM KIRI: FORMULIR TAMBAH EVENT -->
        <div class="form-card">
            <form action="{{ route('events.store') }}" method="POST" id="create-event-form">
                @csrf

                <!-- SEKSI 1: INFORMASI DASAR ACARA -->
                <div class="form-section">
                    <h2 class="section-title">Informasi Dasar Acara</h2>

                    <!-- Nama Instansi / Acara -->
                    <div class="form-group">
                        <label for="event_name" class="form-label">
                            Nama Instansi / Acara <span class="required-dot">*</span>
                        </label>
                        <input type="text" 
                               name="event_name" 
                               id="event_name" 
                               class="form-control @error('event_name') is-invalid @enderror" 
                               placeholder="Contoh: Dinas Pendidikan" 
                               value="{{ old('event_name') }}" 
                               required 
                               autofocus>
                        @error('event_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jenis Pemesanan (Tipe Acara) -->
                    <div class="form-group">
                        <label for="event_type" class="form-label">
                            Jenis Pemesanan (Tipe Acara)
                        </label>
                        <input type="text" 
                               name="event_type" 
                               id="event_type" 
                               class="form-control @error('event_type') is-invalid @enderror" 
                               placeholder="Contoh: Banquet Order, Rapat Yayasan, dll" 
                               value="{{ old('event_type') }}">
                        @error('event_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Baris: Tanggal Pelaksanaan & Waktu Mulai -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="event_date" class="form-label">
                                Tanggal Pelaksanaan <span class="required-dot">*</span>
                            </label>
                            <input type="date" 
                                   name="event_date" 
                                   id="event_date" 
                                   class="form-control @error('event_date') is-invalid @enderror" 
                                   value="{{ old('event_date', date('Y-m-d')) }}" 
                                   required>
                            @error('event_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="start_time" class="form-label">
                                Waktu (Mulai) <span class="required-dot">*</span>
                            </label>
                            <input type="time" 
                                   name="start_time" 
                                   id="start_time" 
                                   class="form-control @error('start_time') is-invalid @enderror" 
                                   value="{{ old('start_time', '08:30') }}" 
                                   required>
                            @error('start_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEKSI 2: DETAIL LOKASI & LAYOUT -->
                <div class="form-section">
                    <h2 class="section-title">Detail Lokasi & Layout</h2>

                    <!-- Pilihan Ruangan -->
                    <div class="form-group">
                        <label for="room_name" class="form-label">
                            Pilihan Ruangan
                        </label>
                        <select name="room_name" id="room_name" class="form-control @error('room_name') is-invalid @enderror">
                            @foreach ($rooms as $room)
                                <option value="{{ $room }}" {{ old('room_name') == $room ? 'selected' : '' }}>
                                    {{ $room }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Baris: Target Tamu & Gaya Susunan Meja -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="estimated_guest_count" class="form-label">
                                Target Tamu (Pax) <span class="required-dot">*</span>
                            </label>
                            <input type="number" 
                                   name="estimated_guest_count" 
                                   id="estimated_guest_count" 
                                   class="form-control @error('estimated_guest_count') is-invalid @enderror" 
                                   placeholder="50" 
                                   min="1" 
                                   value="{{ old('estimated_guest_count', 50) }}" 
                                   required>
                            @error('estimated_guest_count')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="room_layout_id" class="form-label">
                                Gaya Susunan Meja (Layout)
                            </label>
                            <select name="room_layout_id" id="room_layout_id" class="form-control @error('room_layout_id') is-invalid @enderror">
                                @foreach ($roomLayouts as $layout)
                                    <option value="{{ $layout->id }}" {{ old('room_layout_id') == $layout->id ? 'selected' : '' }}>
                                        {{ $layout->layout_name }} (Maks. {{ $layout->max_capacity }} Pax)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_layout_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="form-group">
                        <label for="notes" class="form-label">
                            Catatan Tambahan (Opsional)
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="3" 
                                  class="form-control @error('notes') is-invalid @enderror" 
                                  placeholder="Instruksi khusus, detail warna taplak meja, atau kebutuhan VIP...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- TOMBOL AKSI FORM -->
                <div class="form-actions">
                    <a href="{{ route('events.index') }}" class="btn-cancel">
                        Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan Event (Draft)
                    </button>
                </div>
            </form>
        </div>

        <!-- KOLOM KANAN: PANEL DEKORATIF BERGARIS VERTIKAL WARNA NAVY -->
        <div class="navy-decorative-panel">
            <!-- Pola Garis Vertikal & Balok Slat Berulang -->
            <div class="navy-stripes-bg"></div>

            <!-- Sinar Vertikal Luminous (Pinstripe Lighting) -->
            <div class="navy-glow-beam"></div>
            <div class="navy-glow-beam-secondary"></div>

            <!-- Soft Ambient Radial Glow -->
            <div class="navy-ambient-circle"></div>

            <!-- Konten Elegan di Atas Garis Vertikal -->
            <div class="navy-panel-content">
                <!-- Bagian Atas Panel -->
                <div class="navy-panel-top">
                    <div class="navy-badge">
                        <span class="navy-badge-dot"></span>
                        BEO Operational System
                    </div>

                    <div class="navy-emblem-wrapper">
                        <svg class="navy-emblem-svg" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Geometric Architectural Monogram -->
                            <rect x="4" y="4" width="40" height="40" rx="8" stroke="rgba(96, 165, 250, 0.4)" stroke-width="2"/>
                            <line x1="14" y1="12" x2="14" y2="36" stroke="#38BDF8" stroke-width="2.5" stroke-linecap="round"/>
                            <line x1="24" y1="12" x2="24" y2="36" stroke="#60A5FA" stroke-width="2" stroke-linecap="round"/>
                            <line x1="34" y1="12" x2="34" y2="36" stroke="#93C5FD" stroke-width="2.5" stroke-linecap="round"/>
                            <circle cx="24" cy="24" r="5" fill="#1E40AF" stroke="#38BDF8" stroke-width="2"/>
                        </svg>
                    </div>
                </div>

                <!-- Bagian Tengah Panel -->
                <div class="navy-panel-center">
                    <h2 class="navy-brand-title">
                        BANQUET
                        <span>EVENT ORDER</span>
                    </h2>
                    <p class="navy-brand-desc">
                        Sistem perancangan & standardisasi tata kelola operasional jamuan resmi dengan presisi alokasi ruangan dan fasilitas.
                    </p>
                </div>

                <!-- Bagian Bawah: Feature Slats Bertingkat -->
                <div class="navy-panel-features">
                    <div class="feature-slat-item">
                        <span class="feature-slat-index">01</span>
                        <span class="feature-slat-text">Integritas Layout & Kapasitas Tamu</span>
                    </div>
                    <div class="feature-slat-item">
                        <span class="feature-slat-index">02</span>
                        <span class="feature-slat-text">Protokol Logistik & Peminjaman CBT</span>
                    </div>
                    <div class="feature-slat-item">
                        <span class="feature-slat-index">03</span>
                        <span class="feature-slat-text">Koordinasi Rundown & Operasional Real-Time</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- FOOTER HALAMAN -->
    <footer class="create-event-footer">
        <div class="footer-left">
            &copy; 2026 CRT (Cita Rasa Terbaik) &mdash; Divisi Pengembangan PPLG. BEO System v1.0.
        </div>
        <div class="footer-right">
            <a href="#">Bantuan Operasional</a>
            <a href="#">Panduan Sistem</a>
        </div>
    </footer>

</div>
@endsection
