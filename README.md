# Madtive Documentation -

Aplikasi manajemen dokumentasi teknis berbasis web yang dirancang untuk memfasilitasi proses peninjauan (review) dan persetujuan (approval) konten sebelum diterbitkan. Proyek ini dibangun menggunakan **Laravel 11** dan **Livewire 3**.

## 🚀 Fitur Utama

- **ADMIN : Dashboard Reviewer Terpusat**: Antarmuka bersih untuk meninjau _Release Notes_, _Roadmap_, _FAQ_, dan _Panduan_.
- **REVIEWER :Sistem Approval & Rejection**:
  - **Approve**: Menerbitkan konten dan secara otomatis memperbarui status proyek terkait.
  - **Reject**: Mengembalikan konten ke status draft dengan catatan revisi spesifik untuk drafter.
  - **Tab Riwayat (History)**: Melacak semua dokumen yang telah diproses (Disetujui/Ditolak) dengan detail catatan revisi.
- **TIM DOKUMENTASI : Pembuatan Konten Dokumentasi**: Tempat membuat Dokumentasi untuk _Release Notes_, _Roadmap_, _FAQ_, dan _Panduan_.
- **CLIENT : Isi Dokumentasi Project Client**: Melihat Perkembangan Project yang sedang dikerjakan _Release Notes_, _Roadmap_, _FAQ_, dan _Panduan_. -**Bug Report**: Laman untuk client bisa melaporkan bug yang ada.
- **UI/UX Modern**: Dibangun dengan **Tailwind CSS**, **Mary UI** dan **Filament**, dilengkapi dengan animasi _staggered_ dan antarmuka yang responsif.
- **Automated Database Management**: Mengintegrasikan generator migrasi dan seeder untuk sinkronisasi struktur database yang sudah ada.

## 🛠️ Stack Teknologi

- **Framework:** [Laravel 12](https://laravel.com)
- **Frontend Interactivity:** [Livewire 4](https://livewire.laravel.com)
  - **[Filament v5]**
- **Styling:** [Tailwind CSS](https://tailwindcss.com)
- **UI Components:** Mary UI / WireUI
- **Database:** MySQL / SQLite
- **Packages:**
  - `kitloong/laravel-migrations-generator` (Migration tools)
  - `orangehill/iseed` (Seeder tools)

## 📸 Tampilan Dashboard

| ![Admin Dashboard](public/screenshot/Admin_Dashboard.png) | ![Tim Dokumentasi](public/screenshot/Tim_Dokumentasi.png) | ![Client](public/screenshot/Client.png) | ![Reviewer](public/screenshot/reviewer.png) |

## 📦 Instalasi

1. **Clone repositori:**
   ```bash
   git clone [https://github.com/username/madtive-documentation.git](https://github.com/username/madtive-documentation.git)
   cd madtive-documentation
   ```

# Instal package PHP

composer install

# Instal & Build assets JavaScript/CSS

npm install
npm run build
cp .env.example .env
php artisan key:generate

# Jalankan migrasi struktur tabel

php artisan migrate

# Jalankan seeder (memasukkan data akun & data awal)

php artisan db:seed

php artisan storage:link

php artisan serve/ npm run dev
