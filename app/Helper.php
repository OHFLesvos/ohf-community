<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\NullableFields;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;

class Helper extends Model implements Auditable
{
    use SoftDeletes;
    use NullableFields;
    use \OwenIt\Auditing\Auditable;

    /**
     * Get the person record associated with the helper.
     */
    public function person()
    {
        return $this->belongsTo('App\Person', 'person_id', 'id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'work_application_date',
        'work_rejection_date',
        'work_starting_date',
        'casework_first_interview_date',
        'casework_second_interview_date',
        'casework_first_decision_date',
        'casework_appeal_date',
        'casework_second_decision_date',
        'casework_vulnerability_assessment_date',
        'casework_card_expiry_date',
        'work_leaving_date',
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
        'work_feedback_wishes',
        'work_leaving_date',
        'endorses_casework',
        'casework_asylum_request_status',
        'casework_has_geo_restriction',
        'casework_has_id_card',
        'casework_has_passport',
        'casework_first_interview_date',
        'casework_second_interview_date',
        'casework_first_decision_date',
        'casework_appeal_date',
        'casework_second_decision_date',
        'casework_vulnerability_assessment_date',
        'casework_vulnerability',
        'casework_card_expiry_date',
        'casework_lawyer_name',
        'casework_lawyer_phone',
        'casework_lawyer_email',
        'shirt_size',
        'shoe_size',
        'urgent_needs',
        'work_needs',
    ];

    /**
     * Scope a query to only include applicants.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApplicants($query)
    {
        return $query
            ->whereNull('work_starting_date')
            ->orWhereDate('work_starting_date', '>', Carbon::today());
    }

    /**
     * Scope a query to only include active helpers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query
            ->whereNotNull('work_starting_date')
            ->whereDate('work_starting_date', '<=', Carbon::today())
            ->where(function($q){
                return $q->whereNull('work_leaving_date')
                    ->orWhereDate('work_leaving_date', '>=', Carbon::today());
            });
    }

    /**
     * Scope a query to only include regular helpers (not on trial).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegular($query)
    {
        return $query
            ->whereNotNull('work_starting_date')
            ->whereDate('work_starting_date', '<=', Carbon::today())
            ->where(function($q){
                return $q->whereNull('work_leaving_date')
                    ->orWhereDate('work_leaving_date', '>=', Carbon::today());
            })
            ->where('work_trial_period', false);
    }

    /**
     * Scope a query to only include helpers on trial.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTrial($query)
    {
        return $query
            ->whereNotNull('work_starting_date')
            ->whereDate('work_starting_date', '<=', Carbon::today())
            ->where(function($q){
                return $q->whereNull('work_leaving_date')
                    ->orWhereDate('work_leaving_date', '>=', Carbon::today());
            })
            ->where('work_trial_period', true);
    }

    /**
     * Scope a query to only include alumni (former helpers).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlumni($query)
    {
        return $query
            ->whereNotNull('work_leaving_date')
            ->whereDate('work_leaving_date', '<', Carbon::today());
    }

    public function getIsActiveAttribute() {
        return $this->work_starting_date != null && $this->work_leaving_date == null;
    }

    public function getIsOnTrialPeriodAttribute() {
        return $this->is_active && $this->work_trial_period;
    }

}
