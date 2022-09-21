<?php

namespace App\Listeners;

use App\Events\UserSelfRegistered;
use App\Mail\UserRegistered;
use App\Mail\UserRegisteredConfirmation;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendUserRegisteredNotification
{
    public function handle(UserSelfRegistered $event): void
    {
        $user = $event->user;

        /** @var Collection $admins */
        $admins = User::where('is_super_admin', true)->get();
        try {
            if ($admins->isNotEmpty()) {
                Mail::to($admins)->send(new UserRegistered($user));
            }
            Mail::to($user)->send(new UserRegisteredConfirmation($user));
        } catch (TransportExceptionInterface $ex) {
            Log::error("Failed to send email to newly registered user $user->email.", $ex);
        }
    }
}
