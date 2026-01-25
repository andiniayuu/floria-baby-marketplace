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

class SellerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('seller')
            ->path('seller')
            ->login()
            ->colors([
                'primary' => Color::Amber,
                'danger' => Color::Red,
                'success' => Color::Green,
                'warning' => Color::Orange,
            ])
            ->brandName('Seller Dashboard')
            ->brandLogo(asset('images/seller-logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/favicon.png'))
            ->discoverResources(in: app_path('Filament/Seller/Resources'), for: 'App\\Filament\\Seller\\Resources')
            ->discoverPages(in: app_path('Filament/Seller/Pages'), for: 'App\\Filament\\Seller\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Seller/Widgets'), for: 'App\\Filament\\Seller\\Widgets')
            ->widgets([
                \App\Filament\Seller\Widgets\SellerStatsOverview::class,
                \App\Filament\Seller\Widgets\SellerRevenueChart::class,
                \App\Filament\Seller\Widgets\SellerLatestOrders::class,
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
                // \App\Http\Middleware\CheckSellerAccess::class,
            ])
            ->authGuard('web')
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Toko Saya',
                'Pesanan',
                'Laporan',
                'Pengaturan',
            ])
            ->userMenuItems([
                // 'profile' => \Filament\Navigation\MenuItem::make()
                //     ->label('Edit Profile')
                //     ->url(fn (): string => route('filament.seller.pages.profile'))
                //     ->icon('heroicon-o-user-circle'),
                // 'store_settings' => \Filament\Navigation\MenuItem::make()
                //     ->label('Pengaturan Toko')
                //     ->url(fn (): string => route('filament.seller.pages.store-settings'))
                //     ->icon('heroicon-o-building-storefront'),
            ])
            ->plugins([
                // Add plugins here if needed
            ]);
    }
}
