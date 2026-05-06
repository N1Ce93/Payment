<?php

namespace App\Providers;

use App\Services\Payments\Contracts\PaymentServiceContract;
use App\Services\Payments\PaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentServiceContract::class, PaymentService::class);
    }

    public function boot(): void
    {
        //
    }
}
