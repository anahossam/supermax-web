<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        // admin: users, lists, audit log. edit: create/change orders and customers. Viewers only read and print.
        Gate::define('admin', fn (User $user) => $user->isAdmin());
        Gate::define('edit', fn (User $user) => $user->canEdit());

        Paginator::useBootstrapFive();
    }
}
