<?php

namespace App\Filament\Pages\Auth;

use App\Livewire\Login;
// use Filament\Pages\Auth\Login;
use Livewire\Features\SupportRedirects\Redirector;
class LoginRedirect extends Login
{
    public function mount()
    {
        // Langsung lempar ke Route Login milik Blade
        // Pastikan route login Blade Anda bernama 'login'
        return redirect()->route('login');
    }
}