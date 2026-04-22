<?php

namespace App\Models;

use App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// 👇 HAPUS: use Illuminate\Database\Eloquent\Model;
// 👇 GANTI DENGAN INI YANG BENAR:
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Filament\Models\Contracts\HasTenants;

use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

// Ubah 'extends Model' menjadi 'extends Authenticatable'
class Login extends Authenticatable implements FilamentUser, HasTenants {
    use HasFactory, Notifiable;

    // 👇 PENTING: Jika tabel di database namanya 'users', wajib tambah ini.
    // Jika tidak ditambah, Laravel akan mencari tabel bernama 'logins'.
    protected $table = "users";

    public function isAdmin() {
        return $this->role === "admin";
    }

    public function isClient() {
        return $this->role === "client";
    }

    public function isTimDokumentasi() {
        return $this->role === "tim_dokumentasi";
    }

    public function isReviewer() {
        return $this->role === "reviewer_internal";
    }
    protected $fillable = ["username", "password"];

    protected $hidden = ["password", "remember_token"];

    protected function casts(): array {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function canAccessPanel(Panel $panel): bool {
        // Ganti 'role' dengan nama kolom di database Anda (misal: 'jabatan', 'level', 'type')
        // Pastikan pengecekan string-nya ('tim_dokumentasi') sama persis dengan di database

        return $this->role === "tim_dokumentasi";
    }

    public function getTenants(Panel $panel): Collection {
        // Kembalikan semua project (karena Anda admin tunggal)
        return Project::withoutGlobalScopes()->get();
    }

    public function canAccessTenant(Model $tenant): bool {
        // Boleh akses semua project
        return true;
    }
    public function client(): HasOne {
        // Pastikan Anda mengarahkan ke Model Client yang benar
        return $this->hasOne(Client::class, "user_id", "id");
    }
}
