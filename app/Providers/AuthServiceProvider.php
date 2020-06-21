<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Http\Constants\UserRole;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /*
         * dashboard management
         */
        Gate::define('dashboard', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN ||   // admin = 1
                $userRole->role === UserRole::ROLE_STAFF || 
                $userRole->role === UserRole::ROLE_VIEW || 
                $userRole->role === UserRole::ROLE_EDITOR;
                
        });
        
        Gate::define('user.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN;   // admin = 1
        });
        
        Gate::define('product.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN ||   // admin = 1
                $userRole->role === UserRole::ROLE_VIEW || 
                $userRole->role === UserRole::ROLE_EDITOR;
        });

        Gate::define('category.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN ||   // admin = 1
                $userRole->role === UserRole::ROLE_VIEW || 
                $userRole->role === UserRole::ROLE_EDITOR;
        });

        Gate::define('setting.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN ||   // admin = 1
                $userRole->role === UserRole::ROLE_VIEW || 
                $userRole->role === UserRole::ROLE_EDITOR;
                $userRole->role === UserRole::ROLE_STAFF;
        });

        Gate::define('customer.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN ||   // admin = 1
                $userRole->role === UserRole::ROLE_STAFF || 
                $userRole->role === UserRole::ROLE_VIEW || 
                $userRole->role === UserRole::ROLE_EDITOR;
        });

        Gate::define('staff.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN ||   // admin = 1
                $userRole->role === UserRole::ROLE_VIEW || 
                $userRole->role === UserRole::ROLE_EDITOR;
        });

        Gate::define('sale.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN ||   // admin = 1
            $userRole->role === UserRole::ROLE_STAFF || 
            $userRole->role === UserRole::ROLE_VIEW || 
            $userRole->role === UserRole::ROLE_EDITOR;
        });
        
        Gate::define('customer_type.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN;   // admin = 1
        });

        Gate::define('customer_owed.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN ||   // admin = 1
                $userRole->role === UserRole::ROLE_VIEW || 
                $userRole->role === UserRole::ROLE_EDITOR;
        });

        Gate::define('customer_map.index', function ($userRole) {
            return $userRole->role === UserRole::ROLE_ADMIN ||   // admin = 1
                $userRole->role === UserRole::ROLE_VIEW || 
                $userRole->role === UserRole::ROLE_EDITOR || 
                $userRole->role === UserRole::ROLE_STAFF;
        });
    }
}
