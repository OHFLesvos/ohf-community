<?php

namespace App\Listeners;

use App\Events\UserSelfRegistered;
use App\Models\User;
use App\Notifications\UserRegistered;
use App\Notifications\UserRegisteredConfirmation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
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
                Notification::send($admins, new UserRegistered($user));
            }
            $user->notify(new UserRegisteredConfirmation($user));
        } catch (TransportExceptionInterface $ex) {
            Log::error("Failed to send email to newly registered user $user->email.", $ex);
        }
    }
}
