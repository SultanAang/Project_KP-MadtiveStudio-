<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

use App\Models\Project;
use App\Models\Client;
use App\Models\Release;
use App\Models\Faq;
use App\Models\Roadmap;
use App\Models\KnowledgeBase;
use App\Models\BugReport;

class AdminDashboard extends Component {
    public $total_active = 0;
    public $total_pending = 0;
    public $total_clients = 0;
    public $total_overdue = 0;
    public $total_unapproved = 0;
    public $total_pending_bugs = 0;

    public function mount() {
        // ,emghitung semua statistik yand ada di halaman admin
        $this->total_active = Project::where("status", "ongoing")->count();
        $this->total_pending = Project::where("status", "pending")->count();
        $this->total_clients = Client::count();
        $this->total_overdue = Project::where("status", "!=", "finished")
            ->where("deadline", "<", now())
            ->count();

        $this->total_unapproved =
            Release::where("is_approve", "draft")->count() +
            Faq::where("is_approve", "draft")->count() +
            Roadmap::where("is_approve", "draft")->count() +
            KnowledgeBase::where("is_approve", "draft")->count();

        $this->total_pending_bugs = BugReport::where("status", "pending")->count();
    }

    // logika untuk Pop up modal
    public $isModalOpen = false;
    public $modalTitle = "";
    public $selectedProjects = []; // menampung list data saat kartu diklik

    public function openList($type) {
        $this->isModalOpen = true;
        $now = now();

        // logika pengambilan data berdasarkan tipe kartu yang diklik
        switch ($type) {
            case "ongoing":
                $this->modalTitle = "Daftar Project Berjalan";
                $this->selectedProjects = Project::with("client")
                    ->where("status", "ongoing")
                    ->get();
                break;

            case "pending":
                $this->modalTitle = "Project Belum Dikerjakan";
                $this->selectedProjects = Project::with("client")
                    ->where("status", "pending")
                    ->get();
                break;

            case "clients":
                $this->modalTitle = "Daftar Total Klien";
                $this->selectedProjects = Client::with("projects")->get();
                break;

            case "overdue":
                $this->modalTitle = "Project Lewat Deadline";
                $this->selectedProjects = Project::with("client")
                    ->where("status", "!=", "finished")
                    ->where("deadline", "<", now())
                    ->get();
                break;

            case "unapproved":
                $this->modalTitle = "Tugas Menunggu Approve";

                $releases = Release::with("project")->where("is_approve", "draft")->get()->map(
                    fn($i) => (object) [
                        "name" => "[Release] " . ($i->title ?? "v" . $i->version),
                        "client" => (object) [
                            "company_name" => "Project " . ($i->project->name ?? "-"),
                        ],
                        "status" => "Review",
                        "progress" => "-",
                    ],
                );

                $faqs = Faq::with("project")->where("is_approve", "draft")->get()->map(
                    fn($i) => (object) [
                        "name" => "[FAQ] " . Str::limit($i->question, 40),
                        "client" => (object) [
                            "company_name" => "Project " . ($i->project->name ?? "-"),
                        ],
                        "status" => "Review",
                        "progress" => "-",
                    ],
                );

                $roadmaps = Roadmap::with("project")->where("is_approve", "draft")->get()->map(
                    fn($i) => (object) [
                        "name" => "[Roadmap] " . ($i->task_name ?? ($i->title ?? "Tanpa Judul")),
                        "client" => (object) [
                            "company_name" => "Project " . ($i->project->name ?? "-"),
                        ],
                        "status" => "Review",
                        "progress" => "-",
                    ],
                );

                $knowledges = KnowledgeBase::with("project")
                    ->where("is_approve", "draft")
                    ->get()
                    ->map(
                        fn($i) => (object) [
                            "name" => "[Panduan] " . ($i->title ?? "Tanpa Judul"),
                            "client" => (object) [
                                "company_name" => "Project " . ($i->project->name ?? "-"),
                            ],
                            "status" => "Review",
                            "progress" => "-",
                        ],
                    );

                // menggabungkan semua tugas menjadi satu daftar panjang
                $this->selectedProjects = collect()
                    ->concat($releases)
                    ->concat($faqs)
                    ->concat($roadmaps)
                    ->concat($knowledges);
                break;

            case "bugs":
                $this->modalTitle = "Laporan Bug Pending";

                $this->selectedProjects = BugReport::with("project")
                    ->where("status", "pending")
                    ->get()
                    ->map(function ($i) {
                        return (object) [
                            "name" =>
                                "[Bug] " .
                                ($i->title ??
                                    Str::limit($i->description ?? "Laporan Tanpa Judul", 40)),
                            "client" => (object) [
                                "company_name" => "Project " . ($i->project->name ?? "-"),
                            ],
                            "status" => "Pending",
                            "progress" => "-",
                        ];
                    });
                break;
        }
    }

    public function closeModal() {
        $this->isModalOpen = false;
        $this->selectedProjects = [];
    }
    #[Layout("admin")]
    public function render() {
        $now = now();
        $total_unapproved =
            Release::where("is_approve", "draft")->count() +
            Faq::where("is_approve", "draft")->count() +
            Roadmap::where("is_approve", "draft")->count() +
            KnowledgeBase::where("is_approve", "draft")->count();

        // menghitung total laporan bug
        $total_pending_bugs = BugReport::where("status", "pending")->count();
        return view("adminDashboard", [
            "total_active" => Project::where("status", "ongoing")->count(),
            "total_clients" => Client::count(),
            "total_pending" => Project::where("status", "pending")->count(),
            "total_unapproved" => $total_unapproved,
            "total_pending_bugs" => $total_pending_bugs,
            // Overdue: Deadline sudah lewat TAPI status belum 'finished'
            "total_overdue" => Project::where("deadline", "<", $now)
                ->where("status", "!=", "finished")
                ->count(),

            //  ---
            "recent_projects" => Project::latest()->take(5)->get(),

            // logika deadline

            "urgent_projects" => Project::whereBetween("deadline", [$now, $now->copy()->addDays(7)])
                ->where("status", "!=", "finished")
                ->orderBy("deadline", "asc")
                ->get(),
        ]);
    }
}
