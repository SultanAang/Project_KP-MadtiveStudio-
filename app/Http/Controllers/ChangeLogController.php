<?php

namespace App\Http\Controllers;

use App\Models\Release;
use Illuminate\Http\Request;

class ChangeLogController extends Controller {
    public function index() {
        // Ambil data yang 'is_visible' = true, urutkan dari yang terbaru
        $releases = Release::where("is_visible", true)->orderBy("published_at", "desc")->get();

        return view("changelog", compact("releases"));
    }
}
