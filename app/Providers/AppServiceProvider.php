<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // <--- 1. Pastikan ada baris ini
use App\Models\Project; // <--- 2. Pastikan ada baris ini
use App\Policies\ProjectPolicy; // <--- 3. Pastikan ada baris ini
class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        // $this->app->bind(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void {
        // 4. Masukkan baris ini di dalam fungsi boot:
        // Gate::policy(Project::class, ProjectPolicy::class);
    }
}
