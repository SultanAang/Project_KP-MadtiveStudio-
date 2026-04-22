<?php

use App\Livewire\Users;
use App\Livewire\Counter;
use App\Livewire\ManageUsers;
use app\Livewire\AdminDashboard;
use App\Livewire\UserManage;
use App\Livewire\ProjectDocumentation;
use App\Livewire\Documentation;
use Illuminate\Support\Facades\Route;
use App\Livewire\ReleaseNotes;
use App\Livewire\ChangeLog; // Import Class Livewire Anda
use Illuminate\Support\Facades\Auth;
use App\Livewire\ProjectManage;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\ChangelogController; // Jangan lupa import ini
use App\Livewire\Reviewer;

Route::get("/", function () {
    return view("login");
});
// Route::get('/users', Users::class);

// Route::get("/login", function () {
//     return view("login");
// });
Route::get("/login", [LoginController::class, "index"])->name("login");
Route::post("/login", [LoginController::class, "store"])->name("store");
Route::post("/logout", function () {
    // 1. Hapus sesi login (baik itu admin atau user biasa)
    Auth::logout();

    // 2. Batalkan sesi browser (keamanan)
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    // 3. Arahkan kembali ke halaman utama (atau halaman login)
    return redirect("/login");
})->name("logout");

Route::middleware(["auth"])->group(function () {
    Route::get("/create", function () {
        return view("users");
    });
    // --- 1. ROLE ADMIN ---
    Route::middleware(["role:admin"])->group(function () {
        // Route::get("/admin", function () {
        //     // Ganti 'admin_wrapper' dengan nama file kamu di resources/views
        //     // Misal file kamu resources/views/dashboard-admin.blade.php, maka tulis 'dashboard-admin'
        //     return view("admin");
        // })->name("adminDashboard");
        // Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
        // Route::get("/admin", AdminDashboard::class)->name("admin.dashboard");
        // Hapus '/dashboard'-nya
        Route::get("/admin", App\Livewire\AdminDashboard::class)->name("admin.dashboard");
        // Halaman List (Tabel)
        Route::get("/admin/users", UserManage::class)->name("manageUser");

        // Halaman Create (Tanpa ID)
        Route::get("/admin/users/create", UserManage::class)->name("admin.users.create");

        // Halaman Edit (Dengan ID)
        Route::get("/admin/users/{id}/edit", UserManage::class)->name("admin.users.edit");
        // Route::get("/release-notes", ReleaseNotes::class);
        // routes/web.php
        Route::get("/admin/projects", ProjectManage::class)->name("admin.projects");
    });

    // --- 2. ROLE CLIENT ---
    Route::middleware(["role:client"])->group(function () {
        // Route::get("/client", function () {
        //     // Ganti 'client_wrapper' dengan nama file kamu
        //     return view("clien");
        // })->name("client.dashboard");
        // Route::get("/dddd    ", Documentation::class)->name("docs");
        Route::get("/release-notes", ReleaseNotes::class)->name("client.dashboard");
        Route::get("/changelog", function () {
            return view("changelog"); // Ini memanggil resources/views/changelog.blade.php
        })->name("changelog");
        Route::get("/faq", function () {
            return view("faqs"); // Ini memanggil resources/views/changelog.blade.php
        })->name("faq");
        // Route Halaman Utama Knowledge Base
        Route::get("/knowledge", function () {
            return view("knowledge");
        });
        // Route Detail Artikel (Nanti kita buat halamannya kalau List sudah jadi)
        Route::get("/knowledge/{slug}", function ($slug) {
            // Sementara return string dulu untuk tes link
            return "Halaman detail untuk: " . $slug;
        });
        Route::get("/roadmap", function () {
            return view("roadmap");
        });
        Route::get("/report", function () {
            return view("reportbug");
        });
    });

    // --- 3. ROLE TIM DOKUMENTASI ---
    Route::middleware(["role:tim_dokumentasi"])->group(function () {
        // Route::get("/panel", function () {
        //     // Ganti 'docteam_wrapper' dengan nama file kamu
        //     return view("panel");
        // });
        // // Route::get("/docTeam", function () {
        // //     // Ganti 'docteam_wrapper' dengan nama file kamu
        // //     return view("docTeam");
        // })->name("docteam.dashboard");
        Route::get("/waiting-room", function () {
            return view("no_project");
        })
            ->name("no.project")
            ->middleware("auth");
    });

    // --- 4. ROLE REVIEWER ---

    Route::middleware(["role:reviewer_internal"])->group(function () {
        // Panggil Class langsung. Livewire akan otomatis mencarikan layoutnya.
        Route::get("/reviewer", Reviewer::class)->name("reviewer");
    });
});
