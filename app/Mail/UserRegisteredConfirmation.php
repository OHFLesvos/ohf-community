<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     */
    public User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->markdown('emails.users.registered_confirmation')
            ->subject(__('userprofile.new_account_registered_at_app_name', ['app_name' => config('app.name')]));
    }
}
