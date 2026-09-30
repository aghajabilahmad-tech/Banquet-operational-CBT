<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Masuk & Registrasi - BEO System</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy-primary: #0F2347;
            --navy-hover: #0A1933;
            --navy-gradient-start: #0B2247;
            --navy-gradient-mid: #0D2D5E;
            --navy-gradient-end: #07162C;
            --blue-accent: #1D4ED8;
            --blue-accent-hover: #1E40AF;
            --slate-bg: #F8FAFC;
            --slate-border: #E2E8F0;
            --slate-border-focus: #1D4ED8;
            --text-heading: #0F172A;
            --text-body: #475569;
            --text-muted: #64748B;
            --text-white: #FFFFFF;
            --error-red: #EF4444;
            --error-bg: #FEF2F2;
            --success-green: #10B981;
            --success-bg: #ECFDF5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: var(--slate-bg);
            color: var(--text-heading);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Container Split Screen */
        .auth-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ================= PANEL KIRI (HERO & BRANDING) ================= */
        .hero-panel {
            flex: 0 0 34%;
            max-width: 480px;
            min-width: 360px;
            background: linear-gradient(170deg, var(--navy-gradient-start) 0%, var(--navy-gradient-mid) 42%, var(--navy-gradient-end) 100%);
            position: relative;
            padding: 60px 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--text-white);
            overflow: hidden;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15);
            z-index: 2;
        }

        /* Ambient Glow di Panel Kiri */
        .hero-panel::before {
            content: '';
            position: absolute;
            top: 40%;
            left: 20%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.28) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(50px);
            pointer-events: none;
            z-index: 1;
        }

        .hero-content-top {
            position: relative;
            z-index: 2;
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-white);
            display: flex;
            gap: 7px;
        }

        .brand-title .highlight {
            color: #93C5FD;
            font-weight: 700;
        }

        .hero-content-center {
            position: relative;
            z-index: 2;
            margin: auto 0;
            padding: 40px 0;
        }

        .hero-headline {
            font-size: 2.75rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin-bottom: 22px;
            color: var(--text-white);
        }

        .hero-description {
            font-size: 0.96rem;
            line-height: 1.68;
            color: rgba(226, 232, 240, 0.82);
            max-width: 380px;
        }

        .hero-content-bottom {
            position: relative;
            z-index: 2;
        }

        .hero-footer {
            font-size: 0.78rem;
            line-height: 1.55;
            color: rgba(148, 163, 184, 0.7);
        }

        /* ================= PANEL KANAN (FORM AUTENTIKASI) ================= */
        .form-panel {
            flex: 1;
            background-color: #FAFAFA;
            /* Pola Garis Vertikal Presisi Mirip Gambar */
            background-image: linear-gradient(90deg, rgba(15, 23, 42, 0.035) 1px, transparent 1px);
            background-size: 78px 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
            position: relative;
            overflow-y: auto;
        }

        .form-card {
            width: 100%;
            max-width: 440px;
            margin: auto;
            position: relative;
            z-index: 3;
        }

        /* ================= TAB SWITCHER (MASUK / REGISTRASI) ================= */
        .tab-switcher {
            background-color: #F1F5F9;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 5px;
            display: flex;
            gap: 4px;
            margin-bottom: 32px;
        }

        .tab-btn {
            flex: 1;
            padding: 10px 16px;
            border: none;
            background: transparent;
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text-muted);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tab-btn:hover {
            color: var(--text-heading);
        }

        .tab-btn.active {
            background-color: var(--text-white);
            color: var(--text-heading);
            font-weight: 700;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.08), 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        /* Header Form */
        .form-header {
            margin-bottom: 26px;
        }

        .form-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 0.92rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Alert Notifikasi */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.88rem;
            margin-bottom: 22px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.45;
        }

        .alert-error {
            background-color: var(--error-bg);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #B91C1C;
        }

        .alert-success {
            background-color: var(--success-bg);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #065F46;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        /* Input Group */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: var(--text-body);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 13px 16px;
            font-size: 0.94rem;
            color: var(--text-heading);
            background-color: #F8FAFC;
            border: 1.5px solid var(--slate-border);
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control::placeholder {
            color: #94A3B8;
        }

        .form-control:focus {
            background-color: #FFFFFF;
            border-color: var(--navy-primary);
            box-shadow: 0 0 0 3px rgba(15, 35, 71, 0.08);
        }

        .form-control.is-invalid {
            border-color: var(--error-red);
            background-color: #FFFDFD;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
        }

        .invalid-feedback {
            color: var(--error-red);
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }

        /* Select / Role Selector */
        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .role-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 12px;
            background: #F8FAFC;
            border: 1.5px solid var(--slate-border);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .role-card .role-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-heading);
        }

        .role-card .role-desc {
            font-size: 0.74rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .role-option input[type="radio"]:checked + .role-card {
            border-color: var(--navy-primary);
            background: #EFF6FF;
            box-shadow: 0 0 0 2px rgba(15, 35, 71, 0.15);
        }

        .role-option input[type="radio"]:checked + .role-card .role-title {
            color: var(--navy-primary);
        }

        /* Password Toggle */
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: var(--text-heading);
        }

        /* Remember Me & Forgot Password Row */
        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 18px 0 24px 0;
            font-size: 0.86rem;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            color: var(--text-body);
        }

        .remember-checkbox input {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            accent-color: var(--navy-primary);
            cursor: pointer;
        }

        .forgot-link {
            color: var(--blue-accent);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--blue-accent-hover);
            text-decoration: underline;
        }

        /* Tombol Submit Utama */
        .btn-submit {
            width: 100%;
            padding: 14px 20px;
            background-color: var(--navy-primary);
            color: var(--text-white);
            font-size: 0.98rem;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(15, 35, 71, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background-color: var(--navy-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(15, 35, 71, 0.35);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Form Animation & Toggling */
        .auth-form-content {
            display: none;
            animation: fadeIn 0.25s ease-in-out;
        }

        .auth-form-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modal Info Lupa Sandi */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-backdrop.show {
            display: flex;
        }

        .modal-box {
            background: #FFFFFF;
            max-width: 420px;
            width: 100%;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .modal-box h3 {
            font-size: 1.25rem;
            color: var(--text-heading);
            margin-bottom: 12px;
            font-weight: 700;
        }

        .modal-box p {
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .btn-close-modal {
            background: var(--navy-primary);
            color: #FFFFFF;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        /* Responsive Design */
        @media (max-width: 960px) {
            .auth-container {
                flex-direction: column;
            }

            .hero-panel {
                flex: none;
                max-width: 100%;
                min-width: 100%;
                padding: 40px 24px;
            }

            .hero-content-center {
                padding: 24px 0;
            }

            .hero-headline {
                font-size: 2.1rem;
            }

            .hero-footer {
                display: none;
            }

            .form-panel {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>

    @php
        // Menentukan tab aktif: jika ada session active_tab atau error dari registrasi, buka tab register
        $isRegisterTab = ($activeTab ?? 'login') === 'register' 
            || old('active_tab') === 'register' 
            || $errors->has('full_name') 
            || $errors->has('role') 
            || $errors->has('password_confirmation') 
            || (request()->is('register') && !$errors->has('login'));
    @endphp

    <div class="auth-container">
        <!-- ================= PANEL KIRI (BRANDING & HEADLINE) ================= -->
        <aside class="hero-panel">
            <div class="hero-content-top">
                <a href="{{ url('/') }}" class="brand-header">
                    <div class="brand-title">
                        <span>BEO</span>
                        <span class="highlight">SYSTEM</span>
                    </div>
                </a>
            </div>

            <div class="hero-content-center">
                <h1 class="hero-headline">Tata Kelola Event yang Presisi</h1>
                <p class="hero-description">
                    Portal terpadu untuk pengajuan Banquet Event Order, peminjaman logistik perlengkapan, serta monitoring realisasi tamu operasional perhotelan.
                </p>
            </div>

            <div class="hero-content-bottom">
                <p class="hero-footer">
                    &copy; 2026 CRT (Cita Rasa Terbaik) &mdash; Divisi Pengembangan PPLG.<br>All rights reserved.
                </p>
            </div>
        </aside>

        <!-- ================= PANEL KANAN (FORM LOGIN & REGISTRASI) ================= -->
        <main class="form-panel">
            <div class="form-card">

                <!-- TAB SWITCHER: MASUK & REGISTRASI BARU -->
                <div class="tab-switcher" role="tablist">
                    <button type="button" 
                            id="tabBtnLogin" 
                            class="tab-btn {{ ! $isRegisterTab ? 'active' : '' }}" 
                            onclick="switchAuthTab('login')">
                        Masuk
                    </button>
                    <button type="button" 
                            id="tabBtnRegister" 
                            class="tab-btn {{ $isRegisterTab ? 'active' : '' }}" 
                            onclick="switchAuthTab('register')">
                        Registrasi Baru
                    </button>
                </div>

                <!-- ALERT PESAN SUKSES / ERROR -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="#059669" stroke-width="2"/>
                            <path d="M7 10L9 12L13 8" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10" cy="10" r="8" stroke="#DC2626" stroke-width="2"/>
                            <path d="M10 6V11" stroke="#DC2626" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="10" cy="14" r="1" fill="#DC2626"/>
                        </svg>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                <!-- ================= 1. FORM LOGIN ================= -->
                <div id="loginSection" class="auth-form-content {{ ! $isRegisterTab ? 'active' : '' }}">
                    <div class="form-header">
                        <h2 class="form-title">Selamat Datang Kembali</h2>
                        <p class="form-subtitle">Masukkan akun Anda untuk mengakses sistem manajemen event.</p>
                    </div>

                    <form action="{{ route('login.post') }}" method="POST" autocomplete="on">
                        @csrf
                        <input type="hidden" name="active_tab" value="login">

                        <!-- Field Identitas (Email atau NIS / NIP) -->
                        <div class="form-group">
                            <label for="login_input" class="form-label">NAMA PENGGUNA</label>
                            <div class="input-wrapper">
                                <input type="text" 
                                       id="login_input" 
                                       name="login" 
                                       class="form-control @error('login') is-invalid @enderror" 
                                       value="{{ old('login') }}" 
                                       placeholder="NAMA PENGGUNA" 
                                       required 
                                       autofocus>
                            </div>
                            @error('login')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Field Kata Sandi -->
                        <div class="form-group">
                            <label for="password_input" class="form-label">Kata Sandi</label>
                            <div class="input-wrapper">
                                <input type="password" 
                                       id="password_input" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" 
                                       required>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password_input', this)" title="Tampilkan sandi">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Ingat Saya & Lupa Sandi -->
                        <div class="form-meta">
                            <label class="remember-checkbox">
                                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                <span>Ingat saya di perangkat ini</span>
                            </label>
                            <a href="javascript:void(0)" class="forgot-link" onclick="openForgotModal()">Lupa kata sandi?</a>
                        </div>

                        <!-- Tombol Submit Login -->
                        <button type="submit" class="btn-submit">
                            Masuk ke Sistem
                        </button>
                    </form>
                </div>

                <!-- ================= 2. FORM REGISTRASI ================= -->
                <div id="registerSection" class="auth-form-content {{ $isRegisterTab ? 'active' : '' }}">
                    <div class="form-header">
                        <h2 class="form-title">Daftar Akun Baru</h2>
                        <p class="form-subtitle">Lengkapi formulir untuk membuat akun BEO System baru.</p>
                    </div>

                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="active_tab" value="register">

                        <!-- Field Nama Lengkap -->
                        <div class="form-group">
                            <label for="reg_full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" 
                                   id="reg_full_name" 
                                   name="full_name" 
                                   class="form-control @error('full_name') is-invalid @enderror" 
                                   value="{{ old('full_name') }}" 
                                   placeholder="cth. Muhammad Fauzan" 
                                   required>
                            @error('full_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>



                        <!-- Field Kata Sandi & Konfirmasi Sandi -->
                        <div class="form-group">
                            <label for="reg_password" class="form-label">Kata Sandi (Minimal 8 Karakter)</label>
                            <div class="input-wrapper">
                                <input type="password" 
                                       id="reg_password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" 
                                       required>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('reg_password', this)">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="reg_password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <div class="input-wrapper">
                                <input type="password" 
                                       id="reg_password_confirmation" 
                                       name="password_confirmation" 
                                       class="form-control" 
                                       placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" 
                                       required>
                            </div>
                        </div>

                        <!-- Tombol Submit Registrasi -->
                        <button type="submit" class="btn-submit" style="margin-top: 24px;">
                            Daftar ke Sistem
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL POPUP LUPA KATA SANDI -->
    <div id="forgotPasswordModal" class="modal-backdrop" onclick="closeForgotModal(event)">
        <div class="modal-box" onclick="event.stopPropagation()">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#1D4ED8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <h3>Bantuan Reset Sandi</h3>
            <p>
                Untuk alasan keamanan data sistem perhotelan & CBT, pemulihan akun dilakukan oleh <strong>Koordinator Laboratorium / Admin Sistem</strong>.
                <br><br>
                Silakan hubungi ruang operasional perhotelan dengan membawa kartu NIS / Kartu Pegawai Anda.
            </p>
            <button type="button" class="btn-close-modal" onclick="closeForgotModal()">Saya Mengerti</button>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC SWITCHER & INTERAKSI -->
    <script>
        // Fungsi Pergantian Tab Masuk / Registrasi Baru
        function switchAuthTab(tab) {
            const btnLogin = document.getElementById('tabBtnLogin');
            const btnRegister = document.getElementById('tabBtnRegister');
            const loginSection = document.getElementById('loginSection');
            const registerSection = document.getElementById('registerSection');

            if (tab === 'login') {
                btnLogin.classList.add('active');
                btnRegister.classList.remove('active');
                loginSection.classList.add('active');
                registerSection.classList.remove('active');

                // Update URL tanpa reload
                if (window.history.pushState) {
                    window.history.pushState({ tab: 'login' }, '', '{{ route('login') }}');
                }
            } else {
                btnRegister.classList.add('active');
                btnLogin.classList.remove('active');
                registerSection.classList.add('active');
                loginSection.classList.remove('active');

                // Update URL tanpa reload
                if (window.history.pushState) {
                    window.history.pushState({ tab: 'register' }, '', '{{ route('register') }}');
                }
            }
        }

        // Toggle Visibilitas Password (Show / Hide)
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                `;
            } else {
                input.type = 'password';
                btn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                `;
            }
        }

        // Modal Lupa Sandi
        function openForgotModal() {
            document.getElementById('forgotPasswordModal').classList.add('show');
        }

        function closeForgotModal(e) {
            if (!e || e.target.id === 'forgotPasswordModal' || e.target.classList.contains('btn-close-modal')) {
                document.getElementById('forgotPasswordModal').classList.remove('show');
            }
        }
    </script>
</body>
</html>
