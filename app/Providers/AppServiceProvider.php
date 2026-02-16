<?php

namespace App\Providers;

use App\Models\SellerRequest;
use App\Observers\SellerRequestObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Observer untuk auto-update role user
        SellerRequest::observe(SellerRequestObserver::class);
    }
}
