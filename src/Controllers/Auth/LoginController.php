<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use \App\Models\User;
use \Illuminate\Http\Request;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected function credentials(\Illuminate\Http\Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['is_valid'] = 1;

        return $credentials;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Launched when user just authenticate
     */
    function authenticated(\Illuminate\Http\Request $request, $user)
    {
        $user->update([
            'last_login_at' => Carbon::now()->toDateTimeString()
        ]);

        session(['lang' => $user->setting('lang')]);
        session(['site_id' => $user->site_id]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \App\Http\Controllers\Auth\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $settings = $this->loadConfigSettings();
        $authentication = $this->getAuthentication($settings, $request);
        $username = $authentication->getUsername();

        $user = User::where($settings['columnUsername'], $username)->first();
        if(isset($user)) {
            Auth::login($user);
            return redirect()->route($settings['routeLogin']);
        } else {
            throw new Exception('No user found');
        }
    }

    /**
     * Attempt to loads connect settings into the application.
     *
     * @return class
     */
    private function getAuthentication($settings, $request) {

        switch ($settings['type']) {
            case 'ldap':
                $login = $this->getLoginRequest($request);
                $password = $this->getPasswordRequest($request);
                $authentication = new Ldap($login, $password);
                break;
            case 'oauth':
               $authentication = new Oauth();
               break;
            default:
            throw new InvalidArgumentException('Please enter in the connect_settings file a valid type');
        }
        return $authentication;
    }


    /**
     * Attempt to get input user into the application.
     *
     * @param  \App\Http\Controllers\Auth\Request  $request
     */
    private function getLoginRequest(Request $request) 
    {
        $login = $request->get('email');
        if(empty($login)){
            throw new InvalidArgumentException('No argument in user request');
        }
        if(!strpos($login, '\\')) {
            $login = 'fr\\'.$login;
        }
        return $login;
    }

    /**
     * Attempt to get input password into the application.
     *
     * @param  \App\Http\Controllers\Auth\Request  $request
     */
    private function getPasswordRequest(Request $request) {
        $password = $request->get('password');
        if(empty($password)){
            throw new InvalidArgumentException('No argument in password request');
        }
        return $password;
    }

    /**
     * Attempt to loads connect settings into the application.
     *
     * @return array
     */
    private function loadConfigSettings() {
        $settings = [];

        $settings['type'] = config('connect_settings.type');
        if (empty($settings['type'])) {
            throw new InvalidArgumentException('Please enter in the connect_settings file a connect type');
        }
        $settings['columnUsername'] = config('connect_settings.database.column');
        if (empty($settings['columnUsername'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the column to compare in the table user');
        }
        $settings['routeLogin'] = config('connect_settings.route.acces');
        if (empty($settings['routeLogin'])) {
            throw new InvalidArgumentException('Please enter in connect_settings the redirection route');
        }
        return $settings;
    }
}
