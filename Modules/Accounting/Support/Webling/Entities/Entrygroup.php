<?php

namespace Modules\Accounting\Support\Webling\Entities;

use Modules\Accounting\Support\Webling\WeblingClient;

class Entrygroup extends WeblingEntity
{
    public function url()
    {
        $webling = resolve(WeblingClient::class);
        return $webling->createUrl('/webling.php#/accounting/' . $this->parent . '/entrygroup/:entrygroup/editor/' . $this->id);
    }

}
