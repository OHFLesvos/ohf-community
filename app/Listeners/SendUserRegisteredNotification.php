<?php

namespace App\Listeners;

use App\Events\UserSelfRegistered;
use App\Mail\UserRegistered;
use App\Mail\UserRegisteredConfirmation;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendUserRegisteredNotification
{
    public function handle(UserSelfRegistered $event): void
    {
        $user = $event->user;

        Log::notice('New user registered.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => request()->ip(),
        ]);

        $admins = User::where('is_super_admin', true)->get();
        try {
            Mail::to($admins)->send(new UserRegistered($user));
            Mail::to($user)->send(new UserRegisteredConfirmation($user));
        } catch (TransportExceptionInterface $ex) {
            Log::error("Failed to send email to newly registered user $user->email.", $ex);
        }
    }
}
