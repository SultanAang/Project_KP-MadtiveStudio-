<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Attributes\Validate;

class UserManage extends Component {
    use WithPagination, WithFileUploads;
    // Properties
    public $name, $username, $email, $password, $role;
    public $userIdToEdit = null;
    public $avatar;

    // Variabel untuk Modal (langsung diikat ke wire:model di view)
    public $myModal2 = false;

    // Opsi Role untuk Dropdown (Supaya rapi di view)
    public $roles = [
        ["id" => "admin", "name" => "Admin System"],
        ["id" => "reviewer_internal", "name" => "Reviewer Internal"],
        ["id" => "tim_dokumentasi", "name" => "Tim Dokumentasi"],
        ["id" => "client", "name" => "Client"],
    ];
    //  #[Validate("image|max:4000")]
    // $validated = $this->validate();
    //     if ($this->avatar) {
    //         $validated["avatar"] = $this->avatar->store("avatar", "public");
    //     }
    #[Layout("admin")]
    public function render() {
        return view("livewire.manage-user", [
            "users" => User::latest()->paginate(10),
        ]);
    }

    // --- FITUR MODAL ---
    public function create() {
        $this->resetInputFields();
        $this->myModal2 = true; // Buka Modal
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        $this->userIdToEdit = $id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = "";

        $this->myModal2 = true; // Buka Modal
    }

    // Tidak perlu fungsi openModal/closeModal manual lagi
    // karena kita pakai wire:model="myModal2"

    private function resetInputFields() {
        $this->userIdToEdit = null;
        $this->name = "";
        $this->username = "";
        $this->email = "";
        $this->password = "";
        $this->role = "";
    }

    public function save() {
        $validatedData = $this->validate([
            "name" => "required|min:3",
            "role" => "required|in:admin,tim_dokumentasi,reviewer_internal,client",
            "username" => [
                "required",
                Rule::unique("users", "username")->ignore($this->userIdToEdit),
            ],
            "email" => [
                "required",
                "email",
                Rule::unique("users", "email")->ignore($this->userIdToEdit),
            ],
            "password" => $this->userIdToEdit ? "nullable|min:6" : "required|min:6",
        ]);

        if (!empty($this->password)) {
            $validatedData["password"] = Hash::make($this->password);
        } else {
            unset($validatedData["password"]);
        }

        if ($this->avatar) {
            $validated["avatar"] = $this->avatar->store("avatar", "public");
        }

        User::updateOrCreate(["id" => $this->userIdToEdit], $validatedData);

        $this->myModal2 = false; // Tutup Modal otomatis

        // PENTING: Reset input setelah simpan agar pas dibuka lagi bersih
        $this->resetInputFields();

        session()->flash("message", $this->userIdToEdit ? "User updated." : "User created.");
    }

    public function delete($id) {
        User::findOrFail($id)->delete();
        session()->flash("message", "User deleted.");
    }
}
