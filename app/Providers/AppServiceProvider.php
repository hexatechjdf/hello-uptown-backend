<?php

namespace App\Providers;
use App\Models\Deal;
use App\Observers\DealObserver;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Deal::observe(DealObserver::class);
    }
}
