<?php

namespace vorgans\ldap_oauth_auth\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

Class LdapOauthInstallPackage extends Command
{
    private array $pathFiles;
    private $checkFile = FALSE;

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
        $this->checkFilesExist();
        $this->callSilent('vendor:publish', ['--tag' => 'ldap-oauth']);
        $this->newLine(1);
        $this->info('The Ldap-Oaut-Auth package was installed successfully !');
        $this->newLine(1);
        $this->info('Please setup connect_settings.php in the config folder.');
    }

    /**
     * apply the user choice
     * 
     * @return void
     */
    private function userChoice() 
    {
        if ($this->confirm("Somes files already exists. Do you want to replace them ?")) {
            if($this->confirm("Do you want replace all files ?")) {
                $this->filesDeleteAll();
            } else {
                if($this->confirm("Do you want select a file and replace it ?")) {
                    $this->filesDelete();
                } else {
                    $this->info('The Ldap-Auth-Auth package installation was cancelled !');
                    exit;
                }
            } 
        } else {
            $this->info('The Ldap-Auth-Auth package installation was cancelled !');
            exit;
        }
    }

    /**
     * Check if file exists.
     *
     * @return void
     */
    private function checkFilesExist()
    {
        foreach ($this->pathFiles as $key => $value) {
            if (File::exists($value))
            {
                    $this->checkFile = TRUE;
            }
        }
        if($this->checkFile == FALSE)
        {
            $this->info('Generation of package files...');
        } else {
            $this->userChoice();
        }
    }

    /**
     * Delete All.
     *
     * @return void
     */
    private function filesDeleteAll()
    {
        foreach ($this->pathFiles as $key => $value) {
            if (File::exists($value))
            {
                    File::delete($value);
            }
        }
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
        $this->pathFiles['AbstractProvider'] = base_path('/vendor/league/oauth2-client/src/Provider/AbstractProvider.php');
    }
}