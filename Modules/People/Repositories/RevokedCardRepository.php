<?php

namespace Modules\People\Repositories;

use Modules\People\Entities\RevokedCard;

class RevokedCardRepository
{
    public function findByCardNumber($cardNo)
    {
        return RevokedCard::where('card_no', $cardNo)->first();
    }
}