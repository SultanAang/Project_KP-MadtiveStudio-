<?php

namespace App\Livewire;

use App\Models\Roadmap;
use Livewire\Component;
use Livewire\Attributes\Url; // <--- 1. Import URL Attribute
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
class Roadmaps extends Component {
    public $filter = "all";

    // 2. Tambahkan Property untuk Project
    #[Url(keep: true)]
    public $project_id = "";
    public $selectedProject;
    public $allProjects;

    public $tabs = [
        "all" => "Semua",
        "in_progress" => "Sedang Dikerjakan",
        "planned" => "Direncanakan",
        "done" => "Selesai",
    ];

    public function setFilter($status) {
        $this->filter = $status;
    }

    #[Title("Roadmap")]
    public function render() {
        // --- LOGIC PENENTUAN PROJECT (Sama seperti ReleaseNotes) ---
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

        // Jika User tidak punya project sama sekali, kembalikan view kosong agar tidak error
        if (!$this->selectedProject) {
            return view("livewire.roadmap", [
                "roadmaps" => collect(),
                "project" => null,
                "allProjects" => collect(),
            ]);
        }
        // -----------------------------------------------------------

        $roadmaps = Roadmap::query()
            // [FIX UTAMA] Filter berdasarkan Project ID yang aktif
            ->where("project_id", $this->selectedProject->id)

            ->when($this->filter !== "all", function ($query) {
                $query->where("status", $this->filter);
            })
            ->where("is_approve", "published")
            // Sorting Case When (Tetap dipertahankan)
            ->orderByRaw(
                "CASE status 
                    WHEN 'in_progress' THEN 1 
                    WHEN 'planned' THEN 2 
                    WHEN 'done' THEN 3 
                    ELSE 4 
                END",
            )
            ->orderBy("eta", "asc")
            ->get();

        return view("livewire.roadmap", [
            "roadmaps" => $roadmaps,
            // Kirim data ini agar Sidebar & Layout berfungsi
            "project" => $this->selectedProject,
            "allProjects" => $this->allProjects,
        ]);
    }
}
