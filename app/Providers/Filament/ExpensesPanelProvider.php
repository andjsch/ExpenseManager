<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\RegisterFirstUser;
use App\Filament\Pages\Tenancy\CreateLedger;
use App\Filament\Pages\Tenancy\EditLedger;
use App\Models\Ledger;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ExpensesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('expenses')
            ->path('')
            ->login(Login::class)
            ->profile(EditProfile::class)
            ->registration(RegisterFirstUser::class)
            ->colors([
                'primary' => Color::Teal,
            ])
            ->viteTheme('resources/css/filament/expenses/theme.css')
            ->brandLogo(asset('resources/logo.svg'))
            ->darkModeBrandLogo(asset('resources/logo_dark.svg'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('resources/favicon.png'))
            ->tenant(Ledger::class)
            ->tenantRegistration(CreateLedger::class)
            ->tenantProfile(EditLedger::class)
            ->tenantMenuItems([
                'profile' => fn(Action $action) => $action->label(__('app.ledger_edit_short')),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->globalSearch(false)
            ->topNavigation()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            fn(): string => view('filament_head_inject'),
        );
    }
}
