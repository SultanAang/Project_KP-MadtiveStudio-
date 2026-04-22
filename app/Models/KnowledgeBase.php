<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeBase extends Model {
    use HasFactory;

    // Tambahkan ini agar bisa simpan data!

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }
    protected $guarded = [];

    protected $fillable = [
        // ... field lain ...
        "title",
        "category",
        "content",
        "slug",
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
    // Relasi untuk mengetahui siapa pembuat dokumen ini
    public function author() {
        return $this->belongsTo(User::class, "created_by");
    }
}
