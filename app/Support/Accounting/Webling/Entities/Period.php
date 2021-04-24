<?php

namespace App\Support\Accounting\Webling\Entities;

class Period extends WeblingEntity
{
    protected static $dates = [
        'from',
        'to',
    ];

    public function accountGroups()
    {
        return $this->hasMany(AccountGroup::class);
    }

    public function entryGroups()
    {
        return $this->hasMany(Entrygroup::class);
    }
}
