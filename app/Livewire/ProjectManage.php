<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\User;
use App\Models\Client; // Pastikan import Model Client
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProjectManage extends Component {
    use WithPagination, WithFileUploads;

    // --- FORM PROPERTIES ---

    #[Validate("required|min:3")]
    public $name = "";

    #[Validate("required|email")]
    public $client_email = "";

    // [BARU] Tambahkan properti Company Name
    #[Validate("required|string|min:3")]
    public $company_name = "";

    #[Validate("nullable|string")]
    public $description = "";

    public $status = "pending";
    public $deadline = null;

    #[Validate("nullable|image|max:2048")]
    public $logo;
    public $projectIdToEdit = null;
    public $oldLogo = null;
    public $myModal = false;
    public $search = "";

    #[Layout("admin")]
    public function render() {
        // Gunakan 'client.user' untuk mencegah error N+1 dan null property
        $projects = Project::with("client.user")
            ->when($this->search, fn($q) => $q->where("name", "like", "%" . $this->search . "%"))
            ->latest()
            ->paginate(10);

        return view("livewire.project-manage", [
            "projects" => $projects,
        ]);
    }

    public function create() {
        $this->resetInputFields();
        $this->myModal = true;
    }

    public function edit($id) {
        $project = Project::findOrFail($id);
        $this->projectIdToEdit = $id;

        $this->name = $project->name;
        // Ambil email dari relasi (gunakan optional biar aman)
        $this->client_email = $project->client?->user?->email ?? "";

        // [BARU] Ambil nama company dari database saat edit
        $this->company_name = $project->client?->company_name ?? "";

        $this->description = $project->description;
        $this->deadline = $project->deadline;
        $this->status = $project->status;
        $this->oldLogo = $project->logo;
        $this->logo = null;

        $this->myModal = true;
    }

    public function save() {
        $this->validate();

        // 1. CEK / BUAT USER LOGIN
        $user = User::where("email", $this->client_email)->first();
        if (!$user) {
            $generatedPassword = Str::random(8);
            $generatedUsername = explode("@", $this->client_email)[0] . rand(100, 999);

            $user = User::create([
                "name" => "Client " . $generatedUsername,
                "email" => $this->client_email,
                "username" => $generatedUsername,
                "password" => Hash::make($generatedPassword),
                "role" => "client",
            ]);

            session()->flash(
                "new_account_info",
                "Akun Client Baru Dibuat! Password: $generatedPassword",
            );
        }

        // 2. CEK / BUAT / UPDATE DATA CLIENT
        // Kita gunakan updateOrCreate agar:
        // - Jika belum ada: Dibuat baru dengan nama company dari input.
        // - Jika sudah ada: Nama company diupdate sesuai input (jika ingin merevisi).
        $client = Client::updateOrCreate(
            ["user_id" => $user->id], // Kunci pencarian
            [
                "company_name" => $this->company_name, // [BARU] Data yang disimpan/diupdate
                // 'address' => $this->address,
                // 'phone' => $this->phone,
            ],
        );

        // 3. SIAPKAN DATA PROJECT
        $data = [
            "name" => $this->name,
            "client_id" => $client->id, // Hubungkan ke ID Client yang baru/ada
            "description" => $this->description,
        ];

        if (!$this->projectIdToEdit) {
            $data["slug"] = Str::slug($this->name);
            $data["status"] = "pending";
            $data["deadline"] = null;
        }

        // [PERBAIKAN] Logika Upload Logo
        if ($this->logo) {
            // Hapus logo lama jika sedang edit dan logo lama ada
            if ($this->projectIdToEdit && $this->oldLogo) {
                // Cek apakah file fisik benar-benar ada sebelum dihapus
                if (Storage::disk("public")->exists($this->oldLogo)) {
                    Storage::disk("public")->delete($this->oldLogo);
                }
            }

            // Simpan file baru dan masukkan path-nya ke array data
            // [PERBAIKAN] Pastikan key array sesuai dengan nama kolom (asumsi: logo_path)
            $data["logo"] = $this->logo->store("project-logos", "public");
        }

        Project::updateOrCreate(["id" => $this->projectIdToEdit], $data);

        $this->myModal = false;
        $this->resetInputFields();

        $msg = $this->projectIdToEdit
            ? "Project & Data Client berhasil diupdate."
            : "Project berhasil dibuat.";
        session()->flash("message", $msg);
    }

    public function delete($id) {
        $project = Project::findOrFail($id);
        $clientId = $project->client_id;

        if ($project->logo) {
            Storage::disk("public")->delete($project->logo);
        }
        $project->delete();

        // Cek apakah client masih punya project lain
        $clientHasOtherProjects = Project::where("client_id", $clientId)->exists();

        if (!$clientHasOtherProjects) {
            $client = Client::find($clientId);
            if ($client) {
                $userId = $client->user_id;
                $client->delete();
                User::where("id", $userId)->delete();
            }
        }
        session()->flash("message", "Project deleted.");
    }

    private function resetInputFields() {
        $this->projectIdToEdit = null;
        $this->name = "";
        $this->client_email = "";
        $this->company_name = ""; // [BARU] Reset field ini juga
        $this->description = "";
        $this->logo = null;
        $this->oldLogo = null;
        $this->status = "pending";
        $this->deadline = null;
        $this->resetValidation();
    }
}
