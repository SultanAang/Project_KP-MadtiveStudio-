<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsClient
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role-nya ADALAH 'client'
        // (Sesuaikan 'client' dengan value yang Anda simpan di database)
        if (Auth::user()->role !== 'client') {
            // Jika bukan client (misal Admin iseng akses), tampilkan Error 403 Forbidden
            abort(403, 'Akses Ditolak. Halaman ini khusus untuk Client.');
        }

        return $next($request);
    }
}