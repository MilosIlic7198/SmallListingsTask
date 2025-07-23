<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use App\Services\ListingService;
use App\Services\CategoryService;
use App\Services\UserService;
use App\Models\Listing;
use Illuminate\Support\ServiceProvider;
use App\Policies\ListingPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ListingService::class, function ($app) {
            return new ListingService();
        });
        $this->app->singleton(CategoryService::class, function ($app) {
            return new CategoryService();
        });
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        Gate::policy(Listing::class, ListingPolicy::class);
    }
}
