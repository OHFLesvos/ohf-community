<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\NullableFields;

class Helper extends Model
{
    use SoftDeletes;
    use NullableFields;

    /**
     * Get the person record associated with the helper.
     */
    public function person()
    {
        return $this->hasOne('App\Person', 'id', 'person_id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'application_date',
        'rejection_date',
        'starting_date',
        'casework_interview_date',
        'casework_first_decision_date',
        'casework_appeal_date',
        'casework_second_decision_date',
        'casework_vulnerable_date',
        'casework_card_expiry_date',
        'leaving_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'responsibilities' => 'array',
    ];

    protected $nullable = [
        'responsibilities',
        'local_phone',
        'other_phone',
        'whatsapp',
        'email',
        'skype',
        'residence',
        'work_application_date',
        'work_rejection_date',
        'work_starting_date',
        'work_background',
        'work_improvements',
        'work_leaving_date',
        'casework_status',
        'casework_geo_restriction',
        'casework_interview_date',
        'casework_first_decision_date',
        'casework_appeal_date',
        'casework_second_decision_date',
        'casework_vulnerable_date',
        'casework_vulnerable',
        'casework_card_expiry_date',
        'casework_lawyer_name',
        'casework_lawyer_phone',
        'casework_lawyer_email',
    ];

    public function getActiveAttribute() {
        return $this->starting_date != null && $this->leaving_date == null;
    }
}
