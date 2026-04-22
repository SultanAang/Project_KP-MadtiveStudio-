<?php

namespace App\Providers\Filament;

use App\Models\Project;
// --- PERHATIKAN BARIS INI ---
// Kita panggil file Dashboard yang baru dibuat tadi secara manual
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Pages\Auth\LoginRedirect;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\Auth\Pages\Login;

class AdminPanelProvider extends PanelProvider {
    public function panel(Panel $panel): Panel {
        return $panel
            ->default()
            ->id("mencoba")
            ->path("contenteditor")
            // ->login()
            // >loginRouteSlug('login')
            ->favicon(asset("img/logo_madtive.jpg"))

            // Opsional: Ganti Logo di Pojok Kiri Atas sekalian
            ->brandLogo(asset("img/logo_madtive.jpg"))
            ->colors([
                "primary" => Color::Blue,
            ])
            ->brandName("Madtive Documentation")
            ->discoverResources(in: app_path("Filament/Resources"), for: "App\\Filament\\Resources")
            ->discoverPages(in: app_path("Filament/Pages"), for: "App\\Filament\\Pages")
            ->pages([
                // Kita panggil class Dashboard secara langsung
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path("Filament/Widgets"), for: "App\\Filament\\Widgets")
            // ->widgets([Widgets\AccountWidget::class, Widgets\FilamentInfoWidget::class])
            ->tenant(Project::class) // <--- PENGATURAN AJAIBNYA
            ->tenantMenu(true) // Menampilkan menu ganti project
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
