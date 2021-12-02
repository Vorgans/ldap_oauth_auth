<?php

namespace vorgans\ldap_oauth_auth\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

Class LdapOauthInstallPackage extends Command
{
    private array $pathFiles;

    protected $signature = 'LdapOauth:install';

    protected $description = 'Install the Ldap-Oaut-Auth package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->loadPathFiles();
        $this->filesDelete();
        $this->callSilent('vendor:publish', ['--tag' => 'ldap-oauth']);
        $this->newLine(1);
        $this->info('The Ldap-Oaut-Auth package was installed successfully !');
        $this->newLine(1);
        $this->info('Please setup connect_settings.php in the config folder.');

    }

    /**
     * Delete File if exists.
     *
     * @return void
     */
    private function filesDelete()
    {
        foreach ($this->pathFiles as $key => $value) {
            if (File::exists($value))
            {
                if ($this->confirm("The [{$key}.php] file already exists. Do you want to replace it?")) 
                {
                    File::delete($value);
                } else {
                    continue;
                }
            }
        }
    }

    /**
     * Private File path.
     *
     * @return array
     */
    private function loadPathFiles()
    {
        $this->pathFiles['connect_settings'] = config_path('connect_settings.php');
        $this->pathFiles['Authentication'] = app_path('Http/Controllers/Auth/Authentication.php');
        $this->pathFiles['Ldap'] = app_path('Http/Controllers/Auth/Ldap.php');
        $this->pathFiles['LoginController'] = app_path('Http/Controllers/Auth/LoginController.php');
        $this->pathFiles['Oauth'] = app_path('Http/Controllers/Auth/Oauth.php');
    }
}