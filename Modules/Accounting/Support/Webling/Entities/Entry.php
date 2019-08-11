<?php

namespace Modules\Accounting\Support\Webling\Entities;

class Entry extends WeblingEntity
{
    public function entryGroup()
    {
        return $this->belongsTo(Entrygroup::class);
    }
}
