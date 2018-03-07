<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Donation extends Model
{
	use NullableFields;

	protected $nullable = [
		'purpose',
		'reference',
    ];

    function donor() {
        return $this->belongsTo('App\Donor');
    }

}
