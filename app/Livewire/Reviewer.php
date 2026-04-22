<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Faq;
use App\Models\Roadmap;
use App\Models\Release;
use App\Models\KnowledgeBase;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;

class Reviewer extends Component {
    public $activeTab = "release";

    // --- FITUR BARU: Variabel untuk mencatat mode tampilan (Antrian vs Riwayat) ---
    public $viewMode = "pending";
    // ------------------------------------------------------------------------------

    public $showRejectModal = false;
    public $rejectId;
    public $rejectType;
    public $rejectionNote = "";

    public $showDetailModal = false;
    public $selectedItem = null;

    #[Title("Reviewer Dashboard")]
    #[Layout("reviewer")]
    public function render() {
        // --- LOGIC PENENTUAN STATUS YANG DIAMBIL ---
        // Jika mode 'pending', ambil 'draft'. Jika mode 'history', ambil 'published' dan 'rejected'
        $statuses = $this->viewMode === "pending" ? ["draft"] : ["published", "rejected"];
        // -------------------------------------------

        // 1. Logic Ambil Data (Ditambah WhereIn untuk mendukung filter status)
        $items = match ($this->activeTab) {
            "release" => Release::with(["project", "author"])
                ->whereIn("is_approve", $statuses)
                ->latest()
                ->get(),
            "roadmap" => Roadmap::with(["project", "author"]) // Jangan lupa with author di sini juga
                ->whereIn("is_approve", $statuses)
                ->orderBy("eta")
                ->get(),
            "faq" => Faq::with(["project", "author"]) // Jangan lupa with author di sini juga
                ->whereIn("is_approve", $statuses)
                ->latest()
                ->get(),
            "knowledge" => KnowledgeBase::with(["project", "author"]) // Jangan lupa with author di sini juga
                ->whereIn("is_approve", $statuses)
                ->latest()
                ->get(),
            "history" => $this->getHistoryData(),
            default => collect(),
        };

        // 2. Logic Hitung Badge (Tetap hanya menghitung 'draft' agar notifikasi akurat)
        $counts = [
            "release" => Release::where("is_approve", "draft")->count(),
            "roadmap" => Roadmap::where("is_approve", "draft")->count(),
            "faq" => Faq::where("is_approve", "draft")->count(),
            "knowledge" => KnowledgeBase::where("is_approve", "draft")->count(),
        ];

        // 3. Return View
        return view("livewire.reviewer_base", [
            "items" => $items,
            "counts" => $counts,
        ]);
    }

    public function setTab($tab) {
        $this->activeTab = $tab;
        $this->selectedItem = null;
        // --- Reset tampilan kembali ke Antrian setiap kali pindah tab ---
        $this->viewMode = "pending";
    }

    public function showDetail($type, $id) {
        $this->selectedItem = $this->getModel($type, $id);
        $this->showDetailModal = true;
    }

    public function approve($type, $id) {
        $model = $this->getModel($type, $id);
        if ($model) {
            $model->update(["is_approve" => "published"]);

            // TRIGGER UPDATE STATUS PROJECT
            if ($model->project) {
                $model->project->checkAndUpdateStatus();
            }

            $this->showDetailModal = false;
            session()->flash("success", "Data berhasil disetujui!");
        }
    }

    public function confirmReject($type, $id) {
        $this->rejectType = $type;
        $this->rejectId = $id;
        $this->rejectionNote = "";

        $this->showDetailModal = false;
        $this->showRejectModal = true;
    }

    public function submitReject() {
        $this->validate([
            "rejectionNote" => "required|min:5|max:500",
        ]);

        $model = $this->getModel($this->rejectType, $this->rejectId);
        if ($model) {
            $model->update([
                "is_approve" => "rejected",
                "rejection_note" => $this->rejectionNote,
            ]);
            $this->showRejectModal = false;
            session()->flash("success", "Data ditolak.");
        }
    }

    private function getModel($type, $id): ?Model {
        return match ($type) {
            "release" => Release::find($id),
            "roadmap" => Roadmap::find($id),
            "faq" => Faq::find($id),
            "knowledge" => KnowledgeBase::find($id),
            default => null,
        };
    }

    private function getHistoryData() {
        $releases = Release::with(["project", "author"])
            ->where("is_approve", "!=", "draft")
            ->get()
            ->map(function ($item) {
                $item->document_type = "release";
                return $item;
            });

        $roadmaps = Roadmap::with(["project", "author"])
            ->where("is_approve", "!=", "draft")
            ->get()
            ->map(function ($item) {
                $item->document_type = "roadmap";
                return $item;
            });

        $faqs = Faq::with(["project", "author"])
            ->where("is_approve", "!=", "draft")
            ->get()
            ->map(function ($item) {
                $item->document_type = "faq";
                return $item;
            });

        $knowledges = KnowledgeBase::with(["project", "author"])
            ->where("is_approve", "!=", "draft")
            ->get()
            ->map(function ($item) {
                $item->document_type = "knowledge";
                return $item;
            });

        // Gabungkan dan urutkan dari yang terbaru
        return $releases
            ->concat($roadmaps)
            ->concat($faqs)
            ->concat($knowledges)
            ->sortByDesc("updated_at")
            ->values();
    }

    // --- FUNGSI BANTUAN UNTUK MEMBUKA MODAL DI TAB HISTORY ---
    private function findHistoryModel($id) {
        // Karena di tab history id bisa berbenturan, kita cari di semua tabel (sebagai fallback sederhana)
        return Release::find($id) ??
            (Roadmap::find($id) ?? (Faq::find($id) ?? KnowledgeBase::find($id)));
    }
}
