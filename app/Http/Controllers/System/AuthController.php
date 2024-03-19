<?php

namespace App\Http\Controllers\System;


use Carbon\Carbon;
use Adldap;
use App\Http\Controllers\System\Foundation\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Hash;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use AuthenticatesUsers, ValidatesRequests;

    protected $useActiveDirectory = true;
    protected $redirectTo = '/';

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


    /**
     * Where to redirect users after login.
     *
     * @var string
     */

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
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		// if (config('keycloak.enable')) {
		// 	return \Socialite::driver('keycloak')->redirect();
		// }

        $pageConfigs = ['blankPage' => true];
        return view('system.auth.login', ['pageConfigs' => $pageConfigs]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);


        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['Username atau Password Salah'],
        ]);
    }
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $validation = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];


        $this->validate($request, $validation, [
            $this->username() . '.required' => 'Invalid credential',
            'password.required' => 'Invalid credential',
        ]);
    }


    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        if ($this->useActiveDirectory && strpos($credentials[$this->username()], '@') === false) {
            $credentials[$this->username()] .= '@bpk.go.id';
        }

        $credentials[$this->username()] = strtolower($credentials[$this->username()]);
        return $credentials;
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $username = $this->username();
		$account  = $request->$username;
        if ($this->useActiveDirectory) {
            $user = User::where(DB::raw('lower(' . $this->username() . ')'), $credentials[$this->username()])->first() ??  null;
        } else {
            $user = User::where($this->username(), $credentials[$this->username()])->first() ??  null;
        }
        // dd($user->password,($this->demoCredentials($credentials) || $this->ldapAuth($credentials) || Hash::check($credentials['password'], $user->password)));
        if ($user && ($this->demoCredentials($credentials) || $this->ldapAuth($credentials) || Hash::check($credentials['password'], $user->password))) {
            session([
                'auth_by_form' => true,
            ]);
            Auth::login($user, $request->remember);
            return true;
        }

        return false;
    }

    protected function demoCredentials($credentials)
    {
        return Hash::check($credentials['password'], config('auth.auth_demo_pass'));
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('dashboard');
    }
    protected function ldapAuth($credentials)
    {
        $auth = Adldap::auth();
        return $auth->attempt($credentials[$this->username()], $credentials['password'], $bindAsUser = true);
    }
}
