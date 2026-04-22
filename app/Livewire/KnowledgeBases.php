<?php

namespace App\Livewire;

use App\Models\KnowledgeBase;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url; // <--- 1. Import ini wajib
use Illuminate\Support\Facades\Auth;
class KnowledgeBases extends Component {
    use WithPagination;

    public $search = "";
    public $category = "";

    // 2. Tambahkan Property untuk Project
    #[Url(keep: true)]
    public $project_id = "";
    public $selectedProject;
    public $allProjects;

    public $showArticleModal = false;
    public $selectedArticle = null;

    public function updatedSearch() {
        $this->resetPage();
    }

    public function openArticle($id) {
        $this->selectedArticle = KnowledgeBase::where('id', $id)
            ->where('project_id', $this->selectedProject->id)
            ->where('is_approve', 'published')
            ->first();

        if ($this->selectedArticle) {
            $this->showArticleModal = true;
        }
    }

    public function closeArticle() {
        $this->showArticleModal = false;
        $this->selectedArticle = null;
    }

    public function render() {
        // --- A. LOGIKA PENENTUAN PROJECT (Standard Pattern) ---
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

        // Jika user belum punya project, return view kosong
        if (!$this->selectedProject) {
            return view("livewire.knowledge-bases", [
                "articles" => collect(), // Kosongkan
                "categories" => collect(),
                "project" => null,
                "allProjects" => collect(),
            ]);
        }
        // -----------------------------------------------------

        $articles = KnowledgeBase::query()
            // [FIX 1] Filter Utama: Hanya milik project aktif
            ->where("project_id", $this->selectedProject->id)
            ->where("is_approve", "published")

            ->when($this->search, function ($query) {
                // [FIX 2] Grouping Search (PENTING!)
                // Kita harus membungkus where/orWhere dalam function($subQuery)
                // Agar logic "OR" tidak mengambil data dari project lain.
                $query->where(function ($subQuery) {
                    $subQuery
                        ->where("title", "like", "%" . $this->search . "%")
                        ->orWhere("content", "like", "%" . $this->search . "%");
                });
            })
            ->when($this->category, function ($query) {
                $query->where("category", $this->category);
            })
            ->latest()
            ->paginate(9);

        // [FIX 3] Ambil kategori HANYA dari project ini juga
        // Jangan tampilkan kategori milik project lain
        $categories = KnowledgeBase::where("project_id", $this->selectedProject->id)
            ->where("is_approve", "published")
            ->select("category")
            ->distinct()
            ->pluck("category");

        return view("livewire.knowledge-bases", [
            "articles" => $articles,
            "categories" => $categories,
            // [FIX 4] Kirim data project ke view untuk Sidebar/Layout
            "project" => $this->selectedProject,
            "allProjects" => $this->allProjects,
        ]);
    }
}
