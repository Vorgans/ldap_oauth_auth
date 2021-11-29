<?php

namespace App\Http\Controllers\Auth;

use Exception;
use InvalidArgumentException;
use App\Http\Controllers\Auth\Authentication;

class Ldap implements Authentication {

    private $login;
    private $password;
    private array $settings;

    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    private function loadConfigSettings() {
        $this->settings['ldapHost'] = config('connect_settings.ldap.LDAP_HOST');
        if (empty($this->settings['ldapHost'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the LDAP_HOST');
        }
        $this->settings['ldapPort'] = config('connect_settings.ldap.LDAP_PORT');
        if (empty($this->settings['ldapPort'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the LDAP_PORT');
        }
    }

    private function initLdapConnect() {

        $this->loadConfigSettings();

        $ldapconn = ldap_connect($this->settings['ldapHost'], $this->settings['ldapPort']);
        if(!isset($ldapconn)){
            throw new InvalidArgumentException('Invalid argument in connect_settings LDAP');
        }
        return $ldapconn;
    }

    public function getUsername(){

        $ldapconn = $this->initLdapConnect();

        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        if(@ldap_bind($ldapconn, $this->login, $this->password)) {
            if(strpos($this->login, '\\')) {
                $ret = explode('\\', $this->login);
                $userLdap = $ret[1];
            }
            return strtoupper($userLdap);
        }else{
            throw new Exception('Invalid Grant user LDAP');
        }
    }
}