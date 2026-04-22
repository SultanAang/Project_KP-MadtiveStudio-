<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model {
    use HasFactory;

    // Tambahkan baris ini!
    // Artinya: "Tidak ada kolom yang dijaga ketat, silakan isi semuanya."
    protected $guarded = [];

    protected $fillable = [
        // ... field lain ...
        "question",
        "answer",
        "category",
        "is_visible",
        "is_approve",
        "rejection_note",
        'created_by',
    ];

    // Helper Scopes agar gampang dipanggil
    public function scopePublished(Builder $query) {
        return $query->where("is_approve", "published");
    }

    public function scopePendingReview(Builder $query) {
        return $query->where("is_approve", "review");
    }

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    // Relasi untuk mengetahui siapa pembuat dokumen ini
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
