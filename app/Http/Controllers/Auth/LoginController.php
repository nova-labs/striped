<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;



    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // This section added to check for SHA
        if ($this->checkSHA($request))
        {
            if ($this->attemptLogin($request)) {

                return $this->sendLoginResponse($request);
            }

        };

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    // Find user, check that old password exists, that password is blank
    // Calculate password hashes the same, update user

    public function checkSHA($request)
    {
        $user = User::where('email', '=', $request->email)->first();

        if ($user && $user->old_password != null && $user->password == 0)
        {
            $password_array = explode(' ', $user->old_password);
            foreach ($password_array as $entry){
                if (substr($entry, 0, 6) == '{SSHA}')
                {
                    $salted_sha = substr($entry, 6);
                }
            }
            if ($salted_sha){
                $long_salt = substr( base64_decode( $salted_sha  ), 20 );

                $password_hash = base64_encode( sha1($request->password.$long_salt, TRUE). $long_salt);
                if ($password_hash == $salted_sha){
                    $user->password = Hash::make($request->password);
                    $user->old_password = null;
                    $user->update();
                    return true;
                }
                else
                    return false;

            } else
            {
                return false;
            }
        }
        else{
            return false;
        }


    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
