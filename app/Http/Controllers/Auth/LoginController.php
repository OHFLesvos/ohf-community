<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserSelfRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TOTPService;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Socialite;

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

    use AuthenticatesUsers {
        redirectPath as laravelRedirectPath;
    }

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
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        // Do your logic to flash data to session...
        session()->flash('login_message', __('Hello :name!', [
            'name' => Auth::user()->name,
        ]));

        // Return the results of the method we are overriding that we aliased.
        return $this->laravelRedirectPath();
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
        if (session('errors') !== null && ! empty(session('errors')->get('code'))) {
            return view('auth.tfa');
        }

        return view('auth.login', [
            'oauth_services' => $this->getOauthServices(),
        ]);
    }

    private function getOauthServices(): array
    {
        return collect(config('auth.socialite.drivers'))
            ->filter(fn (string $driver) => config('services.'.$driver) !== null
                && array_elements_not_blank(config('services.'.$driver), ['client_id', 'client_secret', 'redirect']))
            ->toArray();
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request, TOTPService $totp)
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
            ])
                ->after(function ($validator) use ($user, $request, $totp) {
                    if (! $totp->verify($user->tfa_secret, $request->code, null, 1)) {
                        $validator->errors()->add('code', __('Invalid code, please repeat.'));
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

    /**
     * Redirect the user to the provider authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($driver)
    {
        try {
            $user = Socialite::driver($driver)->user();
        } catch (Exception $e) {
            return redirect()->route('login');
        }

        // check if they're an existing user
        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser = new User();
            $newUser->provider_name = $driver;
            $newUser->provider_id = $user->getId();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->email_verified_at = now();
            $newUser->avatar = $user->getAvatar();
            $newUser->locale = \App::getLocale();
            $newUser->save();

            event(new UserSelfRegistered($newUser));

            auth()->login($newUser, true);

            session()->flash('login_message', __('Hello :name. Thanks for registering with :app_name. Your account has been created, and the administrator has been informed, in order to grand you the appropriate permissions.', [
                'name' => Auth::user()->name,
                'app_name' => config('app.name'),
            ]));

            return redirect($this->laravelRedirectPath());
        }

        return redirect($this->redirectPath());
    }
}
