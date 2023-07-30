<?php

namespace App\Support\Accounting\Webling\Entities;

use App\Support\Accounting\Webling\WeblingClient;

class Entrygroup extends WeblingEntity
{
    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function url()
    {
        $webling = resolve(WeblingClient::class);

        return $webling->createUrl('/webling.php#/accounting/'.$this->parent.'/entrygroup/:entrygroup/editor/'.$this->id);
    }
}
