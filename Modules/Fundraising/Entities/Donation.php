<?php

namespace Modules\Fundraising\Entities;

use Illuminate\Database\Eloquent\Model;

use Iatstuti\Database\Support\NullableFields;

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

    function donor() {
        return $this->belongsTo('Modules\Fundraising\Entities\Donor');
    }

}
