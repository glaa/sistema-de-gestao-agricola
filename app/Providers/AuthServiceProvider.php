<?php

namespace App\Providers;
use Laravel\Passport\Passport;
use App\Policies\UserPolicy;
use App\Policies\AssociacaoPolicy;
use App\Models\Associacao;
use App\Models\User;
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
        User::class => UserPolicy::class,
        Associacao::class => AssociacaoPolicy::class,
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
         * Inclusao para API
        */
        Passport::routes();
        //
    }
}
