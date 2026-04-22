<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// use APP

class Client extends Model {
    protected $fillable = ["user_id", "company_name", "address", "phone", "logo"];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function login() {
        return $this->belongsTo(Login::class);
    }
    public function projects() {
        return $this->hasMany(Project::class, "client_id", "id");
    }
}
