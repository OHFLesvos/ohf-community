<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * Get all of the owning transactionable models.
     */
    public function transactionable()
    {
        return $this->morphTo();
    }
}
