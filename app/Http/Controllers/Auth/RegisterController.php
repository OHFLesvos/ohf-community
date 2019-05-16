<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Mail\UserRegistered;
use App\Mail\UserRegisteredConfirmation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers{
        redirectPath as laravelRedirectPath;
    }

    /**
     * Where to redirect users after registration.
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
        session()->flash('login_message', __('userprofile.registration_message', [
            'name' => Auth::user()->name,
            'app_name' => Config::get('app.name')
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:191',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:191',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'pwned',
                'confirmed',
            ]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'is_super_admin' => User::count() == 0,
            'locale' => \App::getLocale(),
        ]);
        Log::notice('New user registered.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => request()->ip(),
        ]);
        $admins = User::where('is_super_admin', true)->get();
        Mail::to($admins)->send(new UserRegistered($user));
        Mail::to($user)->send(new UserRegisteredConfirmation($user));
        return $user;
    }
}
