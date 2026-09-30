<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Memastikan hanya user dengan role admin atau guru (hak akses administrator) yang dapat mengakses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.');
        }

        // 2. Cek apakah peran pengguna memiliki hak akses Admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses Ditolak: Halaman Tambah Event BEO hanya dapat diakses oleh Administrator / Guru Pembina.');
        }

        return $next($request);
    }
}
