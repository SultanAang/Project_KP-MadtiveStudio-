<?php

namespace App\Livewire;

// namespace View\Livewire;
use Views\Livewire;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Login extends Component {
    #[Validate("required|min:5")]
    public $username = "";
    #[Validate("required|min:6")]
    public $password = "";

    public function login() {
        // Menjalankan validasi berdasarkan atribut #[Rule]
        $validated = $this->validate();
        if (Auth::attempt(["username" => $this->username, "password" => $this->password])) {
            session()->regenerate();

            $role = Auth::user()->role;
            $user = auth::user();

            Filament::auth()->login($user);
            return match ($role) {
                "admin" => redirect()->route("admin.dashboard"),
                "client" => redirect()->route("client.dashboard"),

                "tim_dokumentasi" => (function () {
                    // Cek dulu apakah project kosong?
                    if (\App\Models\Project::count() === 0) {
                        return redirect()->route("no.project"); // Lempar ke Ruang Tunggu
                    }

                    // Jika ada project, lempar ke Filament Panel
                    // Pastikan ID panel benar ('mencoba' atau 'admin' atau yg lain)
                    return redirect()->to(Filament::getPanel("mencoba")->getUrl());
                })(),
                "reviewer_internal" => redirect()->route("reviewer"),

                default => redirect("/"), // Jaga-jaga jika role tidak dikenali
            };

            session()->flash("success", "Anda Sudah Login");
            // return redirect()->intended('/users');
            // $this->addError("login", "Username atau password yang Anda masukkan salah.");
        }
        if (!Auth::attempt(["username" => $this->username, "password" => $this->password])) {
            // Cara 1: Menggunakan addError (Akan ditangkap oleh @error('login'))
            $this->addError("login", "Username atau password yang Anda masukkan salah.");

            // ATAU Cara 2: Menggunakan session (Akan ditangkap oleh session('error'))
            // session()->flash('error', 'Username atau password yang Anda masukkan salah.');

            return;
        }

        // Ji   ka gagal, tambahkan error manual
        // $this->addError('username', 'Email atau password yang Anda masukkan salah.');
        $this->reset();
    }

    public function mount() {
        if (Auth::check()) {
            // Langsung arahkan berdasarkan role jika sudah login
            return redirect()->to($this->getRedirectUrlByRole());
        }
    }
    private function getRedirectUrlByRole(): string {
        // Ambil role dari user yang sedang login saat ini
        $role = Auth::user()->role;

        // Gunakan match() untuk menentukan URL tujuan
        // Silakan sesuaikan nama string 'admin', 'client', dll dengan isi database Anda
        // Silakan sesuaikan juga '/url-tujuan' dengan routing aplikasi Anda
        return match ($role) {
            "admin" => "/admin", // Role 1
            "reviewer" => "/reviewer", // Role 2
            "client" => "/release-notes", // Role 3

            // Role 3
            // Role 3
            "tim_dokumentasi" => "/contentEditor", // Role 4
            // default => "/dashboard", // Fallback jika role tidak dikenali
        };
    }

    public function logout() {
        Auth::logout();

        // 2. Batalkan sesi browser (keamanan)
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
    public function render() {
        return view("livewire.login", [
            "title" => "Welcome Page",
            "users" => Login::all(),
        ]);
    }
}
