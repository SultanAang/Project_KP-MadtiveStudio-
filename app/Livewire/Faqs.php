<?php

namespace App\Livewire;

use App\Models\Faq;
use Livewire\Component;
use Livewire\Attributes\Url; // <--- 1. Wajib Import ini
use Illuminate\Support\Facades\Auth;
class Faqs extends Component {
    public $search = "";

    // 2. Tambahkan Property untuk Project
    #[Url(keep: true)]
    public $project_id = "";
    public $selectedProject;
    public $allProjects;

    public function render() {
        // --- A. LOGIKA PENENTUAN PROJECT ---
        $user = auth::user();
        $this->allProjects = $user->client?->projects ?? collect();

        // Cek URL atau Default Project
        if ($this->project_id) {
            $this->selectedProject = $this->allProjects->where("id", $this->project_id)->first();
        }

        if (!$this->selectedProject) {
            $this->selectedProject = $this->allProjects->first();
            if ($this->selectedProject) {
                $this->project_id = $this->selectedProject->id;
            }
        }

        // Jika user belum punya project, return kosong
        if (!$this->selectedProject) {
            return view("livewire.faqs", [
                "faqs" => collect(),
                "project" => null,
                "allProjects" => collect(),
            ]);
        }
        // -----------------------------------

        $faqs = Faq::where("is_visible", true)
            // [FIX 1] Filter Utama: Hanya milik project yang aktif
            ->where("project_id", $this->selectedProject->id)

            ->where(function ($query) {
                // Logic pencarian tetap aman karena dibungkus function
                $query
                    ->where("question", "like", "%" . $this->search . "%")
                    ->orWhere("answer", "like", "%" . $this->search . "%");
            })
            ->where("is_approve", "published")
            ->orderBy("category")
            ->get();

        return view("livewire.faqs", [
            "faqs" => $faqs,
            // [FIX 2] Kirim data project ke view agar Sidebar/Dropdown berfungsi
            "project" => $this->selectedProject,
            "allProjects" => $this->allProjects,
        ]);
    }
}
