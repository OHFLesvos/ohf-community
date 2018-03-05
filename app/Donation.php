<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\NullableFields;

class Donation extends Model
{
    use SoftDeletes;
	use NullableFields;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

	protected $nullable = [
		'purpose',
		'reference',
    ];

    function donor() {
        return $this->belongsTo('App\Donor');
    }

}
