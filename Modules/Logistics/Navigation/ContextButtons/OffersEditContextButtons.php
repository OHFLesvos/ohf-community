<?php

namespace Modules\Logistics\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class OffersEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $offer = $view->getData()['offer'];
        return [
            'delete' => [
                'url' => route('logistics.offers.destroy', $offer),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $offer),
                'confirmation' => __('logistics::offers.confirm_delete_offer')
            ],
            'back' => [
                'url' => route('logistics.suppliers.show', $offer->supplier),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $offer->supplier)
            ],
        ];
    }

}
