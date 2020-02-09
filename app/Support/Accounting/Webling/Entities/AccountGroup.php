<?php

namespace App\Support\Accounting\Webling\Entities;

class AccountGroup extends WeblingEntity
{
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
