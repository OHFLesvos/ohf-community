<?php

namespace App\Support\Accounting\Webling\Entities;

class Entry extends WeblingEntity
{
    public function entryGroup()
    {
        return $this->belongsTo(Entrygroup::class);
    }
}
