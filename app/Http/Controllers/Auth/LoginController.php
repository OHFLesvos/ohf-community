<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserSelfRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TOTPService;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
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
     * @return \Illuminate\Http\Response|View
     */
    public function showLoginForm()
    {
        if (session('errors') !== null && ! empty(session('errors')->get('code'))) {
            return view('auth.tfa');
        }

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @return RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response|View
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
                    if (! $totp->verify($user->tfa_secret, $request->code)) {
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
    public function redirectToProvider(string $driver)
    {
        $args = [];
        if ($driver == 'google') {
            $orgDomain = config('services.google.organization_domain');
            if (filled($orgDomain)) {
                $args['hd'] = $orgDomain;
            }
        }

        return Socialite::driver($driver)
            ->with($args)
            ->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function handleProviderCallback(string $driver)
    {
        try {
            $socialUser = Socialite::driver($driver)->user();
        } catch (Exception $e) {
            Log::error('Socialite login with driver '.$driver.' failed. '.$e->getMessage());

            return redirect()
                ->route('login')
                ->with('error', __('Login with :service failed. Please try again or inform the administrator.', [
                    'service' => ucfirst($driver),
                ]));
        }

        if ($driver == 'google') {
            $orgDomain = config('services.google.organization_domain');
            if (filled($orgDomain) && ! str_ends_with($socialUser->getEmail(), "@$orgDomain")) {
                return redirect()
                    ->route('login')
                    ->with('error', __('Only email addresses of the organization :domain are accepted.', [
                        'domain' => $orgDomain,
                    ]));
            }
        }

        /** @var User $user */
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'password' => Str::random(32),
                'provider_name' => $driver,
                'provider_id' => $socialUser->getId(),
                'locale' => \App::getLocale(),
            ]
        );

        // Update name, avatar in case they have changed on the provider side
        $user->name = $socialUser->getName();
        $user->avatar = $this->getAvatar($socialUser->getAvatar(), $user->avatar);

        // Mark email as verified
        if ($user->email_verified_at == null) {
            $user->email_verified_at = now();
        }

        if ($user->isDirty()) {
            $user->save();
        }

        if ($user->wasRecentlyCreated) {
            Log::info('New user registered with OAuth provider.', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'provider' => $driver,
                'client_ip' => request()->ip(),
            ]);

            event(new UserSelfRegistered($user));
        }

        Auth::login($user);

        if ($user->wasRecentlyCreated) {
            session()->flash('login_message', __('Hello :name. Thanks for registering with :app_name. Your account has been created, and the administrator has been informed, in order to grant you the appropriate permissions.', [
                'name' => Auth::user()->name,
                'app_name' => config('app.name'),
            ]));

            return redirect($this->laravelRedirectPath());
        }

        return redirect($this->redirectPath());
    }

    private function getAvatar(?string $newAvatar, ?string $currentAvatar): ?string
    {
        if ($newAvatar === null) {
            return null;
        }

        if (! ini_get('allow_url_fopen')) {
            return $newAvatar;
        }

        $avatar = 'public/avatars/'.basename($newAvatar);
        if ($currentAvatar !== null && $avatar != $currentAvatar && Storage::exists($currentAvatar)) {
            Storage::delete($currentAvatar);
        }
        Storage::put($avatar, file_get_contents($newAvatar));

        return $avatar;
    }
}
