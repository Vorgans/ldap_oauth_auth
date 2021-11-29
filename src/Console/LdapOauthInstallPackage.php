<?php

namespace vorgans\ldap_oauth_auth\Console;

use Illuminate\Console\Command;

Class LdapOauthInstallPackage extends Command
{
    protected $signature = 'LdapOauth:install';

    protected $description = 'Install the Ldap-Oaut-Auth package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('vendor:publish', ['--tag' => 'ldap-oauth']);
        $this->info('The Ldap-Oaut-Auth package was installed successfully.');
    }
}