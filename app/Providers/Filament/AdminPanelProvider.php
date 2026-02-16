<?php

namespace App\Providers\Filament;

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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Red,
                'success' => Color::Green,
                'warning' => Color::Orange,
            ])
            ->authGuard('admin')
            ->brandName('Floria Baby')
            ->brandLogo(asset('images/baby-boy.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/favicon.png'))
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                \App\Filament\Admin\Widgets\AdminStatsOverview::class,
                \App\Filament\Admin\Widgets\AdminRevenueChart::class,
                \App\Filament\Admin\Widgets\AdminLatestOrders::class,
            ])
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
            ->authMiddleware([
                Authenticate::class,
                // \App\Http\Middleware\CheckAdminAccess::class,
            ])
            ->authGuard('web')
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Katalog Produk',
                'Manajemen Produk',
                'Transaksi',
                'Manajemen User',
                'Laporan & Statistik',
                'Pengaturan',
            ])
            ->userMenuItems([
                // 'profile' => \Filament\Navigation\MenuItem::make()
                //     ->label('Edit Profile')
                //     ->url(fn (): string => route('filament.admin.pages.profile'))
                //     ->icon('heroicon-o-user-circle'),
                // 'settings' => \Filament\Navigation\MenuItem::make()
                //     ->label('Settings')
                //     ->url(fn (): string => route('filament.admin.pages.settings'))
                // ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->plugins([
                // Add plugins here if needed
            ]);
    }
}
