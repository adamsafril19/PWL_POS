<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

try {
    DB::connection()->getPdo();
    Log::info("Database connected successfully: " . DB::connection()->getDatabaseName());
} catch (\Exception $e) {
    Log::error("Database connection failed: " . $e->getMessage());
}

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
        //
    }
}
