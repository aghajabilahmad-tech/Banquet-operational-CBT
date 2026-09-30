@extends('layouts.app')

@section('title', 'BEO Detail - ' . $event->event_name)

@push('styles')
<style>
    body {
        overflow-y: auto !important;
        height: auto !important;
        min-height: 100vh;
    }

    .detail-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 30px;
        z-index: 2;
        position: relative;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .detail-header-card {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 40px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.04);
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }

    .detail-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .detail-code-badge {
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #1E40AF;
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        padding: 6px 16px;
        border-radius: 30px;
        display: inline-block;
        margin-bottom: 12px;
    }

    .detail-title {
        font-size: 2.4rem;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.2;
        margin-bottom: 8px;
    }

    .detail-subtitle {
        font-size: 1.1rem;
        color: #64748B;
        font-weight: 600;
    }

    .detail-status-badge {
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Tombol Cancel Event (Khusus Admin / Guru) */
    .btn-cancel-event {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #FEF2F2;
        color: #DC2626;
        border: 1.5px solid #FECACA;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .btn-cancel-event:hover {
        background: #DC2626;
        color: #FFFFFF;
        border-color: #DC2626;
        box-shadow: 0 4px 14px rgba(220, 38, 38, 0.25);
        transform: translateY(-1px);
    }

    /* Banner Informasi Pembatalan */
    .cancel-info-banner {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        border-left: 5px solid #EF4444;
        border-radius: 12px;
        padding: 18px 22px;
        margin-top: 24px;
        color: #991B1B;
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .cancel-icon {
        font-size: 1.4rem;
        line-height: 1;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .cancel-banner-title {
        font-size: 1rem;
        font-weight: 700;
        color: #991B1B;
        margin-bottom: 4px;
    }

    .cancel-meta {
        font-size: 0.82rem;
        color: #B91C1C;
        margin-bottom: 8px;
    }

    .cancel-reason-box {
        font-size: 0.9rem;
        background: rgba(255, 255, 255, 0.7);
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #7F1D1D;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 25px;
        padding-top: 25px;
        border-top: 1px solid #F1F5F9;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        color: #64748B;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .info-value {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0F172A;
    }

    .placeholder-content-box {
        background: #FFFFFF;
        border-radius: 20px;
        border: 2px dashed #CBD5E1;
        padding: 50px 30px;
        text-align: center;
        color: #64748B;
        margin-bottom: 30px;
    }

    .placeholder-icon {
        font-size: 2.8rem;
        margin-bottom: 15px;
        color: #94A3B8;
    }

    .placeholder-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 8px;
    }

    .placeholder-desc {
        font-size: 0.95rem;
        max-width: 600px;
        margin: 0 auto 20px auto;
        line-height: 1.6;
    }

    .back-btn-row {
        margin-top: auto;
        padding-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #0F172A;
        color: #FFFFFF;
        text-decoration: none;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #1E40AF;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(30, 64, 175, 0.2);
    }

    /* Modal Konfirmasi Pembatalan */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .modal-backdrop.active {
        opacity: 1;
    }

    .modal-card {
        background: #FFFFFF;
        border-radius: 20px;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
        transform: scale(0.95);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modal-backdrop.active .modal-card {
        transform: scale(1);
    }

    .modal-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 24px 28px 18px 28px;
        border-bottom: 1px solid #F1F5F9;
    }

    .modal-header-icon {
        width: 44px;
        height: 44px;
        background: #FEF2F2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .modal-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 2px;
    }

    .modal-subtitle {
        font-size: 0.85rem;
        color: #64748B;
        line-height: 1.4;
    }

    .modal-body {
        padding: 22px 28px;
    }

    .modal-textarea {
        width: 100%;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1.5px solid #E2E8F0;
        font-family: inherit;
        font-size: 0.9rem;
        color: #0F172A;
        outline: none;
        resize: vertical;
        transition: border-color 0.2s;
    }

    .modal-textarea:focus {
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        padding: 16px 28px 22px 28px;
        border-top: 1px solid #F1F5F9;
        background: #F8FAFC;
    }

    .btn-modal-cancel {
        padding: 10px 20px;
        border-radius: 8px;
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        font-weight: 600;
        font-size: 0.88rem;
        color: #64748B;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-cancel:hover {
        background: #F1F5F9;
        color: #0F172A;
    }

    .btn-modal-confirm {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 8px;
        background: #DC2626;
        border: none;
        font-weight: 700;
        font-size: 0.88rem;
        color: #FFFFFF;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
        transition: all 0.2s;
    }

    .btn-modal-confirm:hover {
        background: #B91C1C;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.35);
    }
</style>
@endpush

@section('content')
<!-- NOTIFIKASI FLASH MESSAGE JIKA ADA -->
@if(session('success'))
    <div class="alert-toast-container" id="toast-success" style="position: fixed; top: 85px; right: 40px; z-index: 99999;">
        <div class="alert-toast" style="display: flex; align-items: center; gap: 14px; background: #ffffff; border-left: 5px solid #10B981; border-radius: 12px; padding: 14px 20px; box-shadow: 0 12px 35px rgba(15, 23, 42, 0.14); color: #1e293b; font-size: 0.95rem; font-weight: 500;">
            <div style="flex-shrink: 0; width: 28px; height: 28px; background: #ECFDF5; color: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div style="flex: 1; line-height: 1.4;">
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
            <button type="button" onclick="document.getElementById('toast-success').remove()" style="background: none; border: none; font-size: 1.3rem; color: #94a3b8; cursor: pointer;">&times;</button>
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

<div class="detail-container">
    <div class="detail-header-card">
        <div class="detail-top">
            <div>
                <span class="detail-code-badge">{{ $event->event_code }}</span>
                <h1 class="detail-title">{{ $event->event_name }}</h1>
                <div class="detail-subtitle">
                    {{ $event->institution?->institution_name ?? 'Banquet Order' }}
                    @if($event->event_type)
                        &bull; <span style="color: #2563EB;">{{ $event->event_type }}</span>
                    @endif
                </div>
            </div>

            <!-- STATUS BADGE & TOMBOL CANCEL KHUSUS ADMIN/GURU -->
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div class="detail-status-badge" style="border: 1px solid {{ $event->status_border }}; color: {{ $event->status_text_color }}; background: {{ $event->status_bg }};">
                    {{ $event->status_label }}
                </div>

                @if(auth()->check() && auth()->user()->isAdmin() && $event->event_status !== 'canceled')
                    <button type="button" class="btn-cancel-event" onclick="openCancelModal()" title="Batalkan Event Ini">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        Batalkan Event
                    </button>
                @endif
            </div>
        </div>

        <!-- INFORMASI JIKA EVENT DIBATALKAN -->
        @if($event->event_status === 'canceled')
            <div class="cancel-info-banner">
                <div class="cancel-icon">⚠️</div>
                <div style="flex: 1;">
                    <div class="cancel-banner-title">Acara BEO Ini Telah Dibatalkan</div>
                    <div class="cancel-meta">
                        Dibatalkan pada {{ $event->canceled_at ? \Carbon\Carbon::parse($event->canceled_at)->translatedFormat('d F Y, H:i') : '-' }} WIB
                        @if($event->canceledBy)
                            oleh <strong>{{ $event->canceledBy->full_name }}</strong> ({{ ucfirst($event->canceledBy->role) }})
                        @endif
                    </div>
                    <div class="cancel-reason-box">
                        <strong>Alasan Pembatalan:</strong> {{ $event->cancel_reason ?? 'Tidak ada keterangan alasan yang disertakan.' }}
                    </div>
                </div>
            </div>
        @endif

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Tanggal Pelaksanaan</span>
                <span class="info-value">{{ $event->formatted_date }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Waktu Acara</span>
                <span class="info-value">
                    {{ substr($event->start_time, 0, 5) }} &mdash; {{ substr($event->end_time, 0, 5) }} WIB
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Ruangan & Layout</span>
                <span class="info-value">
                    {{ $event->room_name ?? 'Grand Ballroom' }} &bull; {{ $event->roomLayout?->layout_name ?? 'Standard Layout' }}
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Estimasi Tamu</span>
                <span class="info-value">{{ $event->estimated_guest_count }} Pax</span>
            </div>
        </div>
    </div>

    <!-- PLACEHOLDER DETAIL BEO -->
    <div class="placeholder-content-box">
        <div class="placeholder-icon">&#128221;</div>
        <div class="placeholder-title">Halaman Detail & Dokumen BEO</div>
        <p class="placeholder-desc">
            Informasi lengkap mengenai lembar kerja Banquet Event Order (BEO), susunan rundown acara, 
            kebutuhan logistik peralatan, menu makanan/minuman, serta riwayat presensi tamu dimuat di sistem ini.
        </p>
        <div style="font-size: 0.85rem; color: #94A3B8;">
            ID Event: <strong>#{{ $event->id }}</strong> &bull; Kode: <strong>{{ $event->event_code }}</strong> &bull; Dibuat oleh: <strong>{{ $event->creator?->full_name ?? 'Administrator' }}</strong>
            @if($event->notes)
                <div style="margin-top: 10px; color: #475569; font-style: italic;">
                    &ldquo;{{ $event->notes }}&rdquo;
                </div>
            @endif
        </div>
    </div>

    <div class="back-btn-row">
        <a href="{{ route('events.index') }}" class="btn-back">
            <span>&larr;</span> Kembali ke Daftar Event
        </a>

        @if(auth()->check() && auth()->user()->isAdmin() && $event->event_status !== 'canceled')
            <button type="button" class="btn-cancel-event" onclick="openCancelModal()" style="padding: 10px 22px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                Batalkan Event
            </button>
        @endif
    </div>
</div>

<!-- MODAL KONFIRMASI PEMBATALAN EVENT (KHUSUS ADMIN / GURU) -->
@if(auth()->check() && auth()->user()->isAdmin() && $event->event_status !== 'canceled')
<div class="modal-backdrop" id="cancelModal" style="display: none;">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-header-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div>
                <h3 class="modal-title">Konfirmasi Pembatalan Event</h3>
                <p class="modal-subtitle">Tindakan ini akan mengubah status BEO menjadi <strong>Canceled</strong>.</p>
            </div>
        </div>

        <form action="{{ route('events.cancel', $event->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <p style="font-size: 0.92rem; color: #475569; margin-bottom: 14px; line-height: 1.5;">
                    Apakah Anda yakin ingin membatalkan event <strong>{{ $event->event_name }}</strong> (Kode: <code>{{ $event->event_code }}</code>)?
                </p>

                <label for="cancel_reason" style="display: block; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #334155; margin-bottom: 6px;">
                    Alasan Pembatalan (Opsional)
                </label>
                <textarea name="cancel_reason" 
                          id="cancel_reason" 
                          rows="3" 
                          class="modal-textarea" 
                          placeholder="Contoh: Klien membatalkan jadwal, kendala operasional, atau permintaan resmi instansi..."></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeCancelModal()">Tutup</button>
                <button type="submit" class="btn-modal-confirm">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    Ya, Batalkan Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCancelModal() {
        const modal = document.getElementById('cancelModal');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }

    function closeCancelModal() {
        const modal = document.getElementById('cancelModal');
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 200);
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('cancelModal');
        if (e.target === modal) {
            closeCancelModal();
        }
    });

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCancelModal();
        }
    });
</script>
@endif

@endsection
