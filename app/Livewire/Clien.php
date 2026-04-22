<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\User;
use App\Models\Project; 
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Clien extends Component {
    use WithPagination, WithFileUploads;

    public $name = ""; 
    public $email = "";
    public $username = "";

    #[Validate("required|min:3")]
    public $company_name = "";

    #[Validate("nullable|string")]
    public $phone = "";

    #[Validate("nullable|string")]
    public $address = "";

    #[Validate("nullable|image|max:2048")]
    public $logo;

    public $clientIdToEdit = null;
    public $oldLogo = null;
    public $myModal = false;
    public $search = "";

    #[Layout("admin")]
    public function render() {
        // Query Search: Cari berdasarkan Nama Perusahaan ATAU Email User
        $clients = Client::with("user")
            ->where("company_name", "like", "%" . $this->search . "%")
            ->orWhereHas("user", function ($q) {
                $q->where("name", "like", "%" . $this->search . "%")->orWhere(
                    "email",
                    "like",
                    "%" . $this->search . "%",
                );
            })
            ->latest()
            ->paginate(10);

        return view("livewire.documentation", [
            "clients" => $clients,
        ]);
    }

    public function create() {
        $this->resetInputFields();
        $this->myModal = true;
    }

    public function edit($id) {
        $client = Client::with("user")->findOrFail($id);

        $this->clientIdToEdit = $id;

        // Load Data Client
        $this->company_name = $client->company_name;
        $this->phone = $client->phone;
        $this->address = $client->address;
        $this->oldLogo = $client->logo;

        // Load Data User (Penting: gunakan optional biar gak error kalau user terhapus manual)
        $this->name = $client->user?->name ?? "";
        $this->email = $client->user?->email ?? "";
        $this->username = $client->user?->username ?? "";

        $this->myModal = true;
    }

    public function save() {
        // 1. Validasi Dinamis (Terutama Unique Email & Username)
        // Kita butuh ID user untuk pengecualian unique saat edit
        $userId = $this->clientIdToEdit ? Client::find($this->clientIdToEdit)->user_id : null;

        $this->validate([
            "company_name" => "required|min:3",
            "name" => "required|min:3", // Nama Orangnya
            "email" => ["required", "email", Rule::unique("users", "email")->ignore($userId)],
            "username" => [
                "required",
                "alpha_dash",
                Rule::unique("users", "username")->ignore($userId),
            ],
            "phone" => "nullable|string",
            "address" => "nullable|string",
            "logo" => "nullable|image|max:2048",
        ]);

        // 2. LOGIKA SAVE USER (Tabel users)
        $userData = [
            "name" => $this->name,
            "email" => $this->email,
            "username" => $this->username,
            "role" => "client",
        ];

        if (!$this->clientIdToEdit) {
            // Jika BARU: Generate Password
            $rawPassword = Str::random(8);
            $userData["password"] = Hash::make($rawPassword);

            $user = User::create($userData);

            // Tampilkan Password ke Admin
            session()->flash(
                "new_account_info",
                "Client Baru: {$this->company_name}. Password Login: {$rawPassword}",
            );
        } else {
            // Jika EDIT: Update data user yang sudah ada
            $client = Client::find($this->clientIdToEdit);
            if ($client && $client->user) {
                $client->user->update($userData);
                $user = $client->user;
            } else {
                // Kasus langka: Client ada tapi User hilang, buat user baru (opsional)
                $rawPassword = Str::random(8);
                $userData["password"] = Hash::make($rawPassword);
                $user = User::create($userData);
            }
        }

        // 3. LOGIKA SAVE CLIENT (Tabel clients)
        $clientData = [
            "user_id" => $user->id,
            "company_name" => $this->company_name,
            "phone" => $this->phone,
            "address" => $this->address,
        ];

        // Handle Logo
        if ($this->logo) {
            if ($this->clientIdToEdit && $this->oldLogo) {
                Storage::disk("public")->delete($this->oldLogo);
            }
            $clientData["logo"] = $this->logo->store("client-logos", "public");
        }

        Client::updateOrCreate(["id" => $this->clientIdToEdit], $clientData);

        $this->myModal = false;
        $this->resetInputFields();
        session()->flash(
            "message",
            $this->clientIdToEdit ? "Data Client diperbarui." : "Client berhasil ditambahkan.",
        );
    }

    public function delete($id) {
        $client = Client::findOrFail($id);

        // Safety Check: Jangan hapus jika masih punya Project!
        if (Project::where("client_id", $id)->exists()) {
            $this->js(
                "alert('Gagal! Client ini masih memiliki Project aktif. Hapus projectnya terlebih dahulu.')",
            );
            return;
        }

        // Hapus Logo
        if ($client->logo) {
            Storage::disk("public")->delete($client->logo);
        }

        // Ambil User ID sebelum client dihapus
        $userId = $client->user_id;

        // Hapus Client
        $client->delete();

        // Hapus User Login-nya juga
        if ($userId) {
            User::where("id", $userId)->delete();
        }

        session()->flash("message", "Data Client dan Akun Login berhasil dihapus.");
    }

    private function resetInputFields() {
        $this->clientIdToEdit = null;
        $this->name = "";
        $this->email = "";
        $this->username = "";
        $this->company_name = "";
        $this->phone = "";
        $this->address = "";
        $this->logo = null;
        $this->oldLogo = null;
        $this->resetValidation();
    }
}
