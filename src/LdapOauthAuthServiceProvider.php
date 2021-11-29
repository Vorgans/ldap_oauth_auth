<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LdapOauthAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/connect_settings.php' => config_path('connect_settings.php'),
            __DIR__.'/Controllers/Auth' => app_path('Http/Controllers/Auth/Authentication.php'),
            __DIR__.'/Controllers/Auth' => app_path('Http/Controllers/Auth/Ldap.php'),
            __DIR__.'/Controllers/Auth' => app_path('Http/Controllers/Auth/LoginController.php'),
            __DIR__.'/Controllers/Auth' => app_path('Http/Controllers/Auth/Oauth.php'),
        ]);
    }
}