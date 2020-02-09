<?php

namespace App\Support\Accounting\Webling\Entities;

class Account extends WeblingEntity
{
    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class);
    }
}
