<?php

namespace App\Models;

use App\Livewire\ReleaseNotes;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\HasName;

class Project extends Model implements HasName {
    protected $fillable = [
        "client_id",
        "name",
        "slug",
        "logo",
        "description",
        "deadline",
        "status",
        "progress",
    ];

    protected $guarded = [];

    public function getFilamentName(): string {
        return $this->name;
    }

    public function client() {
        // Pastikan Model Client atau User sesuai dengan aplikasi Anda
        return $this->belongsTo(Client::class);
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    // Project punya banyak Roadmap
    public function roadmaps(): HasMany {
        return $this->hasMany(Roadmap::class);
    }

    public function faqs(): HasMany {
        return $this->hasMany(Faq::class);
    }

    public function knowledge(): HasMany {
        return $this->hasMany(KnowledgeBase::class);
    }

    public function releases(): HasMany {
        return $this->hasMany(Release::class);
    }
    public function bugReports() {
        return $this->hasMany(BugReport::class);
    }

    public function checkAndUpdateStatus() {
        // 1. Hitung TOTAL semua tugas (Release, FAQ, Roadmap, Knowledge)
        $totalTasks =
            $this->releases()->count() +
            $this->faqs()->count() +
            $this->roadmaps()->count() +
            $this->knowledge()->count();

        // 2. Jika belum ada tugas sama sekali -> PENDING & Progress 0%
        if ($totalTasks === 0) {
            $this->update([
                "status" => "pending",
                "progress" => 0,
            ]);
            return;
        }

        // 3. Hitung tugas yang SUDAH DI-APPROVE (is_approve = 'published')
        $approvedTasks =
            $this->releases()->where("is_approve", "published")->count() +
            $this->faqs()->where("is_approve", "published")->count() +
            $this->roadmaps()->where("is_approve", "published")->count() +
            $this->knowledge()->where("is_approve", "published")->count();

        // 4. Hitung persentase Progress
        $progressPercentage = round(($approvedTasks / $totalTasks) * 100);

        // 5. Tentukan Status berdasarkan perbandingan tugas
        if ($approvedTasks === $totalTasks) {
            // Semua tugas sudah di-approve -> FINISHED
            $this->update([
                "status" => "finished",
                "progress" => $progressPercentage,
            ]);
        } else {
            // Ada tugas, tapi belum selesai semua -> ONGOING
            $this->update([
                "status" => "ongoing",
                "progress" => $progressPercentage,
            ]);
        }
    }
}
