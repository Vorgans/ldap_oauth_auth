<?php

namespace vorgans\ldap_oauth_auth\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use vorgans\ldap_oauth_auth\Console\LdapOauthInstallPackage;

class LdapOauthAuthServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            LdapOauthInstallPackage::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__.'/../config/connect_settings.php' => config_path('connect_settings.php'),
            __DIR__.'/../Controllers/Auth/Authentication.php' => app_path('Http/Controllers/Auth/Authentication.php'),
            __DIR__.'/../Controllers/Auth/Ldap.php' => app_path('Http/Controllers/Auth/Ldap.php'),
            __DIR__.'/../Controllers/Auth/LoginController.php' => app_path('Http/Controllers/Auth/LoginController.php'),
            __DIR__.'/../Controllers/Auth/Oauth.php' => app_path('Http/Controllers/Auth/Oauth.php'),
            __DIR__.'/AbstractProvider.php' => base_path('/vendor/league/oauth2-client/src/Provider/AbstractProvider.php'),
        ], 'ldap-oauth');
    }
}
