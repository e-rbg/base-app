<?php
namespace App\Providers;

use App\Models\User;
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
        // Super Admin can do everything
        Gate::before(function ($user, $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        // Specific permissions
        Gate::define('access-admin-panels', function (User $user) {
            return in_array($user->role, ['super_admin', 'admin', 'editor']); // Admin and Editor can access admin panels, but only Super Admin can do everything.
        });

        Gate::define('delete-content', function (User $user) {
            return $user->role === 'super_admin'; // Only Admin can delete, Editor cannot.
        });
    }
}
