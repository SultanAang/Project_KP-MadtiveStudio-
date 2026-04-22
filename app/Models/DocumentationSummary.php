<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationSummary extends Model
{
    // Karena ini View, kita matikan fitur tulis/edit
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // Arahkan ke nama View tadi
    protected $table = 'documentation_summaries';

    // Relasi ke Project tetap bisa jalan!
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}