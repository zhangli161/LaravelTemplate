<?php

namespace App\Providers;

//use Carbon\Carbon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
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
	    Passport::routes();

// token有效期
	    Passport::tokensExpireIn(Carbon::now()->addDays(1));
// 可刷新token时间
	    Passport::refreshTokensExpireIn(Carbon::now()->addDays(2));
	
    }
}
