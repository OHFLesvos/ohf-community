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
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('New user account registered at :app_name', [
                        'app_name' => config('app.name'),
                    ]))
                    ->greeting(__('Registration confirmation'))
                    ->line(__('Dear :name.', [
                        'name' => $this->user->name,
                    ]))
                    ->line(__('Thanks for registering an account at :app_name with your e-mail address :email.', [
                        'app_name' => config('app.name'),
                        'email' => $this->user->email,
                    ]))
                    ->action(__('View your profile'), route('userprofile'));
    }
}
