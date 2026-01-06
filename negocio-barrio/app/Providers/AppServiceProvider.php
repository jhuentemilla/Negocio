<?php

namespace App\Providers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Observers\SaleObserver;
use App\Observers\SaleItemObserver;
use App\Observers\StockMovementObserver;
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
        Sale::observe(SaleObserver::class);
        SaleItem::observe(SaleItemObserver::class);
        StockMovement::observe(StockMovementObserver::class);
    }
}
