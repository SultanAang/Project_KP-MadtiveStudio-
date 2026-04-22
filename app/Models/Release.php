<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Release extends Model {
    // 1. Izinkan kita mengisi semua kolom secara massal (lewat Filament)
    protected $guarded = [];

    // 2. Casting (Penerjemah Data)
    protected $casts = [
        "features" => "array", // JSON Database -> Array PHP
        "published_at" => "date", // String Tanggal -> Objek Tanggal
        "is_visible" => "boolean", // 1/0 -> True/False
    ];

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    protected $fillable = [
        // ... field lain ...
        "version",
        "title",
        "features",
        "intro_text",
        "publish_at",
        "",
        "is_approve",
        "rejection_note",
        "created_by",
    ];

    // Helper Scopes agar gampang dipanggil
    public function scopePublished(Builder $query) {
        return $query->where("is_approve", "published");
    }

    public function scopePendingReview(Builder $query) {
        return $query->where("is_approve", "review");
    }

    // Relasi untuk mengetahui siapa pembuat dokumen ini
    public function author() {
        return $this->belongsTo(User::class, "created_by");
    }
}
