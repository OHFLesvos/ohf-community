<?php

namespace App\Listeners;

use App\Events\UserSelfRegistered;
use App\Mail\UserRegistered;
use App\Mail\UserRegisteredConfirmation;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendUserRegisteredNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserSelfRegistered  $event
     * @return void
     */
    public function handle(UserSelfRegistered $event)
    {
        $user = $event->user;

        Log::notice('New user registered.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => request()->ip(),
        ]);

        $admins = User::where('is_super_admin', true)->get();
        Mail::to($admins)->send(new UserRegistered($user));
        Mail::to($user)->send(new UserRegisteredConfirmation($user));
    }
}
