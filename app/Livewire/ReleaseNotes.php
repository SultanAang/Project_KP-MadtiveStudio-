<?php

namespace App\Livewire;

use App\Models\Release;
use App\Models\Client;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
class ReleaseNotes extends Component {
    public $feedback = "";

    // Variable untuk menyimpan ID versi yang dipilih
    public $selectedVersionId;
    // Menangkap ?project_id=... dari URL
    #[Layout("release_notes")]
    #[Url(keep: true)]
    public $project_id = "";

    public $selectedProject;
    public $allProjects;

    #[Title("Release Notes")]
    public function render() {
        // 1. FIX: SAYA UNCOMMENT INI (Wajib ada biar tidak error Undefined Variable)
        $user = auth::user();

        // 2. Ambil semua project milik Client
        $this->allProjects = $user->client?->projects ?? collect();

        // 3. LOGIKA PENENTUAN PROJECT AKTIF
        if ($this->project_id) {
            $this->selectedProject = $this->allProjects->where("id", $this->project_id)->first();
        }

        // Jika tidak ada di URL atau ID salah, pakai Project Pertama
        if (!$this->selectedProject) {
            $this->selectedProject = $this->allProjects->first();

            // Set ID agar URL otomatis update
            if ($this->selectedProject) {
                $this->project_id = $this->selectedProject->id;
            }
        }

        // --- JIKA USER BELUM PUNYA PROJECT SAMA SEKALI ---
        if (!$this->selectedProject) {
            return view("livewire.release_notes", [
                "versionList" => collect(),
                "selectedRelease" => null,
                "project" => null,
                "allProjects" => collect(),
            ]);
        }

        // 4. LOGIKA MEMILIH VERSI DEFAULT
        if (!$this->selectedVersionId) {
            $latest = Release::where("is_visible", true)
                ->where("project_id", $this->selectedProject->id)
                ->where("is_approve", "published") // <--- Tambahkan syarat published
                ->latest()
                ->first();

            if ($latest) {
                $this->selectedVersionId = $latest->id;
            }
        }

        // 5. QUERY DAFTAR VERSI
        $versionList = Release::where("is_visible", true)
            ->where("project_id", $this->selectedProject->id)
            ->orderBy("published_at", direction: "desc")
            ->where("is_approve", "published")
            ->latest()
            ->get(["id", "version", "published_at"]);

        // 6. QUERY DETAIL VERSI
        $selectedRelease = Release::where("id", $this->selectedVersionId)
            ->where("project_id", $this->selectedProject->id)
            ->where("is_approve", "published")
            ->first();

        // 7. RETURN VIEW TANPA ->layout()
        // Livewire akan otomatis memakai components/layouts/app.blade.php
        // Kita kirim data project & allProjects ke View agar bisa diakses layout jika perlu
        return view("livewire.release_notes", [
            "versionList" => $versionList,
            "selectedRelease" => $selectedRelease,
            "project" => $this->selectedProject, // Kirim data ini ke view
            "allProjects" => $this->allProjects, // Kirim data ini ke view
        ]);
    }

    public function sendFeedback() {
        if (!$this->feedback) {
            return;
        }

        session()->flash("success", "Terima kasih! Masukan Anda telah kami terima.");
        $this->feedback = "";
    }
}
