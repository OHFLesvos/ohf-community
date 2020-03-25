<?php

namespace App\Navigation\ContextButtons\Helpers;

use App\Models\Helpers\Helper;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HelperShowContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $helper = $view->getData()['helper'];
        return [
            'person' => [
                'url' => route('people.show', $helper->person),
                'caption' => __('people.view_person'),
                'icon' => 'users',
                'authorized' => Auth::user()->can('view', $helper->person),
            ],
            'vcard' => [
                'url' => route('people.helpers.vcard', $helper),
                'caption' => __('app.vcard'),
                'icon' => 'address-card',
                'authorized' => Auth::user()->can('view', $helper),
            ],
            'delete' => [
                'url' => route('people.helpers.destroy', $helper),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $helper),
                'confirmation' => 'Really delete this helper?',
            ],
            'back' => [
                'url' => route('people.helpers.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Helper::class),
            ],
        ];
    }

}
