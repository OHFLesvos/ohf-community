<?php

namespace Modules\Accounting\Support\Webling\Entities;

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
