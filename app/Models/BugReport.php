<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BugReport extends Model {
    protected $guarded = ["id"];

    protected $fillable = [
        "user_id",
        "project_id",
        "title",
        "description",
        "priority",
        "status",
        "screenshot_path",
        "admin_note", // <--- WAJIB ADA
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    // Relasi ke Project (Project yang bermasalah)
    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }
}
