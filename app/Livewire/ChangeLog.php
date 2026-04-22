<?php

namespace App\Livewire;

use App\Models\Release;
use Livewire\Component;

class ChangeLog extends Component {
    public function render() {
        $releases = Release::where("is_visible", true)->orderBy("published_at", "desc")->get();
        return view("livewire.changelog", [
            "releases" => $releases,
        ]);
    }
}
