<?php

namespace App\Navigation\ContextButtons\UserManagement;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\View\View;

class UserProfile2FAContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('userprofile'),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => true,
            ],
        ];
    }
}
