<?php

namespace App\Providers;

use App\Models\Client;
use Laravel\Sanctum\Sanctum;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::authenticateAccessTokensUsing(function ($token, $isValid) {
            return Client::find($token->tokenable_id);
        });
    }
}
