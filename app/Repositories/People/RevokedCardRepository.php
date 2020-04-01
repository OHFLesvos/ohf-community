<?php

namespace App\Repositories\People;

use App\Models\People\RevokedCard;

class RevokedCardRepository
{
    public function findByCardNumber($cardNo)
    {
        return RevokedCard::where('card_no', $cardNo)->first();
    }
}
