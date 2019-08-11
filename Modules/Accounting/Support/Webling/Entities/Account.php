<?php

namespace Modules\Accounting\Support\Webling\Entities;

class Account extends WeblingEntity
{
    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class);
    }
}
