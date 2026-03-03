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
        Gate::before(function ($user, $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        Gate::define('access-admin-panels', function (User $user) {
            return in_array($user->role, ['super_admin', 'admin', 'editor']); 
        });

        Gate::define('delete-content', function (User $user) {
            return $user->role === 'super_admin';
        });

        Gate::define('manage-users', fn(User $u) => in_array($u->role, [
            User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN,
        ]));
    }
}
