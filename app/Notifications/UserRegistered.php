<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification
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
                    ->subject(__('New user registered: :name', [
                        'name' => $this->user->name,
                    ]))
                    ->greeting(__('User registered'))
                    ->line(__('The user :name (:email) has created a new account.', [
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                    ]))
                    ->action(__('View User'), route('users.show', $this->user));
    }
}
