<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use OTPHP\TOTP;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
     * @return string
     */
	protected function redirectTo()
	{
		return route('home');
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
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (session('errors') !== null && !empty(session('errors')->get('code'))) {
            return view('auth.tfa');
        }
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // 2FA Authentication
        $user = User::where('email', $request->email)->first();
        if ($user != null && $user->tfa_secret != null) {
            if (empty($request->code)) {
                return view('auth.tfa');
            }
            $validator = Validator::make($request->all(), [
                'code' => 'required|numeric',
            ])->after(function ($validator) use ($user, $request) {
                $otp = TOTP::create($user->tfa_secret);
                if (!$otp->verify($request->code)) {
                    $validator->errors()->add('code', __('userprofile.invalid_code_please_repeat'));
                }                
            });
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
        }

        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            Log::info('User logged in successfully.', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'client_ip' => request()->ip(),
            ]);
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        Log::info('User failed to login.', [
            'email' => $request->email,
            'client_ip' => request()->ip(),
        ]);

        return $this->sendFailedLoginResponse($request);
    }

}
