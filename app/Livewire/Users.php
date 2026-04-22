<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Users extends Component {
    #validasi langsung menggunakan Livewire
    use WithFileUploads, WithPagination;

    public function toggleDropdown() {
        $this->isDropdownOpen = !$this->isDropdownOpen;
    }

    public function selectRole($value) {
        $this->role = $value;
        $this->isDropdownOpen = false; // Tutup dropdown setelah memilih
    }

    public $isDropdownOpen = false;
    #[Validate("required|min:4")]
    public $name = "";
    #[Validate("required|min:4")]
    public $username = "";
    #[Validate("required|email:dns|unique:users")]
    public $email = "";
    #[Validate("required|min:3")]
    public $password = "";
    #[Validate("required|in:admin,client,tim_dokumentasi,reviewer_internal")]
    public $role = "";
    #[Validate("image|max:4000")]
    public $avatar;
    // public $title = 'Users Page';
    public function createNewUser() {
        // dd('button clicked!');
        // $this->validate();
        $validated = $this->validate();
        if ($this->avatar) {
            $validated["avatar"] = $this->avatar->store("avatar", "public");
        }
        User::create([
            "name" => $this->name,
            "email" => $this->email,
            "username" => $this->username,
            "role" => $this->role,
            "password" => Hash::make($this->password),
            "avatar" => $validated["avatar"],
        ]);
        $this->reset();
        session()->flash("succes", "Akun Telah Dibuat");
        return redirect()->intended("/login");

        
    }
    public function save() {}
    public function render() {
        return view("livewire.users", [
            "title" => "Welcome Page",
            "users" => User::latest("created_at")->paginate(6),
        ]);
    }
}
