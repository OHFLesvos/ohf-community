<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegisteredConfirmation extends Notification
{
    use Queueable;

    public function __construct(public readonly User $user)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject(__('Welcome to :app_name', [
                        'app_name' => config('app.name'),
                    ]))
                    ->greeting(__('Registration confirmation'))
                    ->line(__('Dear :name.', [
                        'name' => $this->user->name,
                    ]))
                    ->line(__('Thanks for registering an account at :app_name with your email address :email.', [
                        'app_name' => config('app.name'),
                        'email' => $this->user->email,
                    ]))
                    ->action(__('View your profile'), route('userprofile'));
    }
}
