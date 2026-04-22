<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Filament\Facades\Filament;

use Filament\Auth\Pages\Login;
// use App\Http\Controllers\Auth\LoginController;

class LoginController extends Controller {
    // Fungsi untuk menampilkan Form (GET)
    public function index() {
        return view("login"); // Pastikan ada file resources/views/login.blade.php
    }

    // Fungsi untuk Proses Login (POST)
    public function store(Request $request) {
        // 1. Validasi Input
        $request->validate([
            "username" => ["required|email"], // Sesuaikan dengan database Anda
            "password" => ["required|6"],
        ]);
        $credentials = $request->only("username", "password");
        // 2. Coba Login (Menggunakan Model 'Login' yang sudah diperbaiki)
        if (auth::attempt($credentials)) {
            $request->session()->regenerate();
            // --- LOGIKA REDIRECT ANDA DISINI ---
            // Ambil user yang sedang login
            $user = auth::user();
            dd('Berhenti! Controller ini jalan. Role user adalah: ' . $user->role);
            Filament::auth()->login($user);
            // Cek Role (Pastikan ada kolom 'role' di tabel Anda)
            if ($user->role === "tim_dokumentasi") {
                $url = Filament::getPanel("mencoba")->getUrl();
                if (\App\Models\Project::count() === 0) {
                    return redirect()->route("no.project"); // Lempar ke Ruang Tunggu
                }
                return redirect()->to($url); // Masuk Filament
            }
            // if ($user->role === "client") {
            //     return redirect()->intended("/release-notes"); // Masuk Filament
            // }

            // Jika BUKAN tim_dokumentasi (Admin/Reviewer/Client)
            // Lempar ke Dashboard biasa (bukan Filament)
            return back()->withErrors(["username" => "Login gagal."]);
        }

        // 3. Jika Gagal
        return back()->withErrors([
            "username" => "Username atau password salah.",
        ]);
    }
}
