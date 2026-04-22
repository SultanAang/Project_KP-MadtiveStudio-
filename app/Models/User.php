<?php

namespace App\Models;

use App\Models\Project;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Filament\Models\Contracts\HasTenants;
// , HasTenants
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

use Illuminate\Database\Eloquent\Model; // <--- INI YANG TADI KURANG
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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
    protected $fillable = ["name", "email", "password", "username", "role", "avatar"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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
    public function client() {
        return $this->hasOne(Client::class, "user_id", "id");
    }
}
