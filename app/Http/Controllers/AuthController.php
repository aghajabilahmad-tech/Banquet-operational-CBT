<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir login.
     */
    public function showLoginForm()
    {
        return view('auth.login', [
            'activeTab' => 'login',
        ]);
    }

    /**
     * Tampilkan formulir registrasi.
     */
    public function showRegistrationForm()
    {
        return view('auth.login', [
            'activeTab' => 'register',
        ]);
    }

    /**
     * Proses autentikasi masuk (Login).
     * Mendukung input berupa NIS, NIP, Username, Nama Lengkap, atau Email.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ], [
            'login.required'    => 'Email, NIS / NIP, atau Nama Lengkap wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($request->input('login'));
        $password   = $request->input('password');
        $remember   = $request->boolean('remember');

        // Cari user berdasarkan username (NIS/NIP), nama lengkap, atau email (jika ada kolom email)
        $user = User::where('username', $loginInput)
            ->orWhere('full_name', $loginInput)
            ->when(Schema::hasColumn('users', 'email'), function ($query) use ($loginInput) {
                $query->orWhere('email', $loginInput);
            })
            ->first();

        // Validasi keberadaan user dan kecocokan password
        if (! $user || ! Hash::check($password, $user->password)) {
            return back()
                ->withInput($request->only('login', 'remember') + ['active_tab' => 'login'])
                ->withErrors([
                    'login' => 'Kombinasi kredensial login dan kata sandi tidak cocok.',
                ]);
        }

        // Cek status keaktifan akun pengguna
        if (! $user->is_active) {
            return back()
                ->withInput($request->only('login') + ['active_tab' => 'login'])
                ->withErrors([
                    'login' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi koordinator lab perhotelan.',
                ]);
        }

        // Lakukan login pengguna dan regenerasi session
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Pindahkan user setelah login ke beranda
        return redirect()->intended(route('home'))
            ->with('success', 'Selamat datang kembali, ' . $user->full_name . '!');
    }

    /**
     * Proses registrasi akun baru siswa.
     * Hanya memerlukan Nama Lengkap & Kata Sandi. Peran otomatis 'siswa'.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal harus terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Generate username unik dari nama lengkap untuk memenuhi kolom NOT NULL UNIQUE di database
        $baseUsername = Str::slug($validated['full_name'], '');
        if (empty($baseUsername)) {
            $baseUsername = 'siswa';
        }

        $username = substr($baseUsername, 0, 40);
        $count = 1;
        while (User::where('username', $username)->exists()) {
            $username = substr($baseUsername, 0, 35) . '_' . $count;
            $count++;
        }

        // Buat data pengguna baru dengan peran default siswa
        $user = User::create([
            'full_name'  => $validated['full_name'],
            'username'   => $username,
            'role'       => 'siswa',
            'class_name' => null,
            'phone'      => null,
            'password'   => Hash::make($validated['password']),
            'is_active'  => true,
        ]);

        // Otomatis login setelah registrasi & regenerasi session
        Auth::login($user);
        $request->session()->regenerate();

        // Pindahkan user setelah registrasi ke beranda
        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil! Selamat datang di BEO System, ' . $user->full_name . '.');
    }

    /**
     * Proses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
