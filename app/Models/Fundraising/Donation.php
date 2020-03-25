<?php

namespace App\Models\Fundraising;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use NullableFields;

    protected $nullable = [
        'purpose',
        'reference',
        'in_name_of',
    ];

    protected $dates = [
        'deleted_at',
        'thanked',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

}
