<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Models\Sale;
use App\Models\CashRegister;
use App\Models\User;
use App\Models\StockMovement;
use App\Policies\ProductPolicy;
use App\Policies\SalePolicy;
use App\Policies\CashRegisterPolicy;
use App\Policies\UserPolicy;
use App\Policies\StockMovementPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Sale::class => SalePolicy::class,
        CashRegister::class => CashRegisterPolicy::class,
        User::class => UserPolicy::class,
        StockMovement::class => StockMovementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Las policies se registran automáticamente gracias al array $policies
    }
}
