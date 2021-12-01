<?php

namespace vorgans\ldap_oauth_auth\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use vorgans\ldap_oauth_auth\Console\LdapOauthInstallPackage;

class LdapOauthAuthServiceProvider extends ServiceProvider
{
    private array $pathFiles;

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
        $this->loadPathFiles();
        $this->filesDelete($this->pathFiles);

        $this->publishes([
            __DIR__.'/../config/connect_settings.php' => $this->pathFiles['settings'],
            __DIR__.'/../Controllers/Auth/Authentication.php' => $this->pathFiles['authentication'],
            __DIR__.'/../Controllers/Auth/Ldap.php' => $this->pathFiles['ldap'],
            __DIR__.'/../Controllers/Auth/LoginController.php' => $this->pathFiles['loginController'],
            __DIR__.'/../Controllers/Auth/Oauth.php' => $this->pathFiles['oauth'],
        ], 'ldap-oauth');
    }

    /**
     * Delete File if exists.
     *
     * @return void
     */
    public function filesDelete($pathFiles)
    {
        foreach ($pathFiles as $key) {
            if (File::exists($key)) 
            {
                File::delete($key);
            }
        }
    }

    /**
     * Delete File if exists.
     *
     * @return array
     */
    public function loadPathFiles()
    {
        $this->pathFiles['settings'] = config_path('connect_settings.php');
        $this->pathFiles['authentication'] = app_path('Http/Controllers/Auth/Authentication.php');
        $this->pathFiles['ldap'] = app_path('Http/Controllers/Auth/Ldap.php');
        $this->pathFiles['loginController'] = app_path('Http/Controllers/Auth/LoginController.php');
        $this->pathFiles['oauth'] = app_path('Http/Controllers/Auth/Oauth.php');

    }
}
