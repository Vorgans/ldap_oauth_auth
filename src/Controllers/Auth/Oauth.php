<?php

namespace App\Http\Controllers\Auth;

use Exception;
use InvalidArgumentException;
use App\Http\Controllers\Auth\Authentication;


class Oauth implements Authentication 
{
    private array $settings;

    private function loadConfigSettings() {
        $this->settings['clientId'] = config('connect_settings.oauth.clientId');
        if (empty($this->settings['clientId'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the clientId');
        }
        $this->settings['resource'] = config('connect_settings.oauth.resource');
        if (empty($this->settings['resource'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the url resource');
        }
        $this->settings['redirectUri'] = config('connect_settings.oauth.redirectUri');
        if (empty($this->settings['redirectUri'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the redirectUri');
        }
        $this->settings['urlAuthorize'] = config('connect_settings.oauth.urlAuthorize');
        if (empty($this->settings['urlAuthorize'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the urlAuthorize');
        }
        $this->settings['urlAccessToken'] = config('connect_settings.oauth.urlAccessToken');
        if (empty($this->settings['urlAccessToken'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the urlAccesToken');
        }
        $this->settings['urlResourceOwnerDetails'] = config('connect_settings.oauth.urlResourceOwnerDetails');
        if (empty($this->settings['urlResourceOwnerDetails'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the urlResourceOwnerDetails');
        }
    }

    private function getAccesToken() {

        $this->loadConfigSettings();

        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $this->settings['clientId'],
            'resource'                => $this->settings['resource'],
            'redirectUri'             => $this->settings['redirectUri'],
            'urlAuthorize'            => $this->settings['urlAuthorize'],
            'urlAccessToken'          => $this->settings['urlAccessToken'],
            'urlResourceOwnerDetails' => $this->settings['urlResourceOwnerDetails'],
        ]);

        $authorizationUrl = $provider->getAuthorizationUrl();
        if (empty($authorizationUrl)) {
            throw new InvalidArgumentException('Invalid Argurment in connect_settings : no acces to url authorize');
        }

        if(isset($authorizationUrl)) {

            header('Location: ' . $authorizationUrl);
            $accesToken = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
            return $accesToken;
        } else {
            throw new Exception('Invalid Grant Authorization Url');
        }
    }

    public function getUsername()
    {

        $accesToken = $this->getAccesToken();
        if (empty($accesToken)) {
            throw new Exception('No acces to token');
        }

        $userToken = $accesToken->getToken();
        if (isset($userToken)) {
            
            $tokenParts = explode(".", $userToken);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);
            $userOauth = $jwtPayload->winaccountname;

            return $userOauth;
        } else {
            throw new Exception('Invalid Grant user token');
        }
    }
}