<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\BugReport as BugModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
class BugReport extends Component {
    use WithFileUploads;

    #[Validate("required|min:5|max:100", as: "Judul Masalah")]
    public $title = "";

    #[Validate("required|min:10", as: "Deskripsi")]
    public $description = "";

    #[Validate("required|in:low,medium,high,critical")]
    public $priority = "medium"; // Max 2MB

    #[Validate("nullable|image|max:2048", as: "Screenshot")]
    public $screenshot; // Validasi wajib pilih

    public $project_id;
    #[Url(keep: true)]
    public $selectedProject;
    public $allProjects;

    // --- Action Methods ---
    // public function mount($currentProjectId) {
    //     $this->project_id = $currentProjectId;
    // }

    public $isFormView = false;
    public $hasReports = false;

    public $showModal = false;
    public $selectedBug = null;

    public function mount() {
        // logika cek apakah user sudah pernah buat laporan
        $reportCount = BugModel::where("user_id", Auth::id())->count();

        if ($reportCount > 0) {
            $this->hasReports = true;
            $this->isFormView = false;
            $this->hasReports = false;
            $this->isFormView = true;
        }
    }

    public function createNewReport() {
        $this->reset(["title", "description", "priority", "screenshot"]);
        $this->isFormView = true;
    }

    public function cancelReport() {
        $this->isFormView = false;
    }
    public function viewDetails($id) {
        $this->selectedBug = BugModel::find($id);
        $this->showModal = true;
    }

    public function closeModal() {
        $this->showModal = false;
        $this->selectedBug = null;
    }

    public function save() {
        $this->validate([
            "title" => "required",
            "description" => "required",
            "project_id" => "required|exists:projects,id",
        ]);
        $path = null;
        if ($this->screenshot) {
            $path = $this->screenshot->store("bugs", "public");
        }
        BugModel::create([
            "user_id" => Auth::id(),
            "project_id" => $this->project_id,
            "title" => $this->title,
            "description" => $this->description,
            "priority" => $this->priority,
            "screenshot_path" => $path,
            "status" => "pending",
        ]);
        $this->reset(["title", "description", "priority", "screenshot"]);

        session()->flash(
            "success",
            "Laporan bug berhasil dikirim! Terima kasih atas masukan Anda.",
        );
        $this->hasReports = true;
        $this->isFormView = false;
    }
    public function render() {
        $user = Auth::user();
        $this->allProjects = $user->client?->projects ?? collect();
        // 1. Cek apakah ada request ID dari URL
        if ($this->project_id) {
            // Cari project tersebut di list milik user (Security Check)
            $this->selectedProject = $this->allProjects->where("id", $this->project_id)->first();
        }
        // 2. Fallback: Jika ID di URL ngaco atau kosong, pilih project pertama
        if (!$this->selectedProject) {
            $this->selectedProject = $this->allProjects->first();

            // Sync balik ID-nya agar properti tidak null
            if ($this->selectedProject) {
                $this->project_id = $this->selectedProject->id;
            }
        }
        $reports = BugModel::where("user_id", $user->id)
            ->where("project_id", $this->project_id)
            ->latest()
            ->get();
        if (!$this->selectedProject) {
            // Bisa redirect atau set error state
            // return redirect()->route('home');
        }
        return view("livewire.bug-report", [
            "reports" => $reports,
        ]);
    }
}
