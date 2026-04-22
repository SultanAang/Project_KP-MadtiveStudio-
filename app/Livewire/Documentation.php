<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class Documentation extends Component {
    public $slug = "home";
    public $search = "";
    #[Layout("documentation")]
    #[Title("Dokumentasi Aplikasi")]
    public function render() {
        // Pengecekan keamanan ganda (Optional, jika middleware jebol)
        // if (Auth::check() && Auth::user()->role !== "client") {
        //     abort(403);
        // }
        return view("livewire.documentation");
    }

    public function mount($slug = "home") {
        $this->slug = $slug;
    }

    // === STRUKTUR MENU 1-16 (Sesuai Request) ===
    public function getMenuProperty() {
        $structure = [
            ["title" => "1. Beranda", "slug" => "home", "icon" => "o-home"],

            [
                "title" => "2. Getting Started",
                "icon" => "o-rocket-launch",
                "subs" => [
                    ["title" => "Tentang Aplikasi", "slug" => "about"],
                    ["title" => "Persyaratan Sistem", "slug" => "requirements"],
                ],
            ],

            [
                "title" => "3. Akses & Autentikasi",
                "icon" => "o-lock-closed",
                "subs" => [
                    ["title" => "Cara Login", "slug" => "login"],
                    ["title" => "Lupa Password", "slug" => "forgot-password"],
                    ["title" => "Logout Aman", "slug" => "logout"],
                ],
            ],

            ["title" => "4. Navigasi & Tampilan", "slug" => "navigation", "icon" => "o-map"],
            ["title" => "5. Dashboard", "slug" => "dashboard", "icon" => "o-chart-bar"],
            ["title" => "6. Akun & Profil", "slug" => "account", "icon" => "o-user"],

            [
                "title" => "7. Fitur Aplikasi",
                "icon" => "o-puzzle-piece",
                "subs" => [
                    ["title" => "Manajemen Data", "slug" => "feat-data"],
                    ["title" => "Manajemen User", "slug" => "feat-user"],
                    ["title" => "Transaksi", "slug" => "feat-trans"],
                    ["title" => "Laporan", "slug" => "feat-report"],
                ],
            ],

            ["title" => "8. Role & Hak Akses", "slug" => "roles", "icon" => "o-identification"],
            [
                "title" => "9. Alur Proses Bisnis",
                "slug" => "business-flow",
                "icon" => "o-arrows-right-left",
            ],

            [
                "title" => "10. Panduan Penggunaan",
                "icon" => "o-book-open",
                "subs" => [
                    ["title" => "Menambah Data", "slug" => "guide-create"],
                    ["title" => "Mengubah Data", "slug" => "guide-edit"],
                    ["title" => "Menghapus Data", "slug" => "guide-delete"],
                ],
            ],

            [
                "title" => "11. Troubleshooting",
                "slug" => "troubleshooting",
                "icon" => "o-wrench-screwdriver",
            ],
            ["title" => "12. FAQ", "slug" => "faq", "icon" => "o-question-mark-circle"],
            ["title" => "13. Keamanan & Privasi", "slug" => "security", "icon" => "o-shield-check"],
            ["title" => "14. Support & Bantuan", "slug" => "support", "icon" => "o-lifebuoy"],
            ["title" => "15. Update & Changelog", "slug" => "changelog", "icon" => "o-bell"],
            ["title" => "16. Legal", "slug" => "legal", "icon" => "o-scale"],
        ];

        // Fitur Search Menu
        if ($this->search) {
            $filtered = [];
            foreach ($structure as $item) {
                if (str_contains(strtolower($item["title"]), strtolower($this->search))) {
                    $filtered[] = $item;
                    continue;
                }
                if (isset($item["subs"])) {
                    $subs = array_filter(
                        $item["subs"],
                        fn($s) => str_contains(strtolower($s["title"]), strtolower($this->search)),
                    );
                    if (count($subs) > 0) {
                        $item["subs"] = $subs;
                        $filtered[] = $item;
                    }
                }
            }
            return $filtered;
        }

        return $structure;
    }

    // === KONTEN HALAMAN (Contoh Isi Sesuai SOP) ===
    public function getContentProperty() {
        return match ($this->slug) {
            "home" => [
                "title" => "Selamat Datang di Portal Dokumentasi",
                "desc" => "Panduan resmi penggunaan aplikasi Madtive System untuk Client.",
                "content" => '
                    <div class="alert alert-info shadow-sm mb-6">
                        <x-icon name="o-information-circle" />
                        <span>Dokumentasi ini disusun untuk pengguna non-teknis agar dapat mengoperasikan aplikasi dengan mudah.</span>
                    </div>
                    <h3>Manfaat Aplikasi</h3>
                    <ul>
                        <li>Mempercepat proses administrasi data.</li>
                        <li>Menyajikan laporan real-time yang akurat.</li>
                        <li>Menjamin keamanan data perusahaan.</li>
                    </ul>
                    <div class="mt-6">
                        <a href="/docs/about" class="btn btn-primary">Mulai Pelajari Aplikasi</a>
                    </div>
                ',
            ],
            "login" => [
                "title" => "Cara Masuk (Login)",
                "desc" => "Panduan langkah demi langkah untuk mengakses sistem.",
                "content" => '
                    <h4>Langkah-langkah Penggunaan</h4>
                    <ol>
                        <li>Buka halaman website aplikasi.</li>
                        <li>Masukkan <strong>Email</strong> yang terdaftar pada kolom Email.</li>
                        <li>Masukkan <strong>Kata Sandi</strong> pada kolom Password.</li>
                        <li>Klik tombol <strong>Masuk</strong>.</li>
                    </ol>
                    <h4>Validasi</h4>
                    <ul>
                        <li><strong>Pesan Error:</strong> "Akun tidak ditemukan."<br>
                        <strong>Solusi:</strong> Pastikan penulisan email sudah benar dan akun sudah didaftarkan oleh Admin.</li>
                    </ul>
                ',
            ],
            // ... Tambahkan case lain sesuai kebutuhan (feat-data, troubleshooting, dll) ...
            default => [
                "title" => "Halaman Belum Tersedia",
                "desc" => "Dokumentasi untuk bagian ini sedang dalam proses penyusunan.",
                "content" => '<div class="text-center py-10 opacity-50">Konten segera hadir.</div>',
            ],
        };
    }
}
