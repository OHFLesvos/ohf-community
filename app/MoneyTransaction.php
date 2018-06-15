<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use AustinHeap\Database\Encryption\Traits\HasEncryptedAttributes;
use OwenIt\Auditing\Contracts\Auditable;

class MoneyTransaction extends Model implements Auditable
{
    use HasEncryptedAttributes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that should be encrypted on save.
     *
     * @var array
     */
    protected $encrypted = [
        'beneficiary',
        'project',
        'description',
    ];
}
