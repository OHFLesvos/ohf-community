<?php

namespace App\Listeners;

use App\Events\UserSelfRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PromoteToSuperAdminIfUndefined
{
    public function handle(UserSelfRegistered $event): void
    {
        if (User::where('is_super_admin', true)->count() > 0) {
            return;
        }

        $this->promote($event->user);
    }

    private function promote(User $user): void
    {
        $user->is_super_admin = true;
        $user->save();

        Log::warning('User promoted to super-admin.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => request()->ip(),
        ]);
    }
}
