<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        //
        Gate::define('update-user', function (User $user, User $obj) {

            if ($user->isAdmin())
                return true;


            if ($user->id != $obj->id)
                return false;

            return true;

        });

        Gate::define('destroy-user', function (User $user, User $obj) {

            if ($user->id === $obj->id)
                return false; //Um usuário não pode apagar a si mesmo

            if ($user->isAdmin())
                return true;

            return false;
        });

        Gate::define('store-user', function (User $user) {

            if ($user->isAdmin())
                return true;

            return false;
        });

        Gate::define('manage-profiles', function (User $user) {

            if ($user->isAdmin())
                return true;

            return false;
        });


    }
}
