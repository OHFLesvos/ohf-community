<?php

namespace App\Models\CommunityVolunteers;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

class CommunityVolunteerResponsibility extends Pivot
{
    use NullableFields;

    protected $table = 'community_volunteers_responsibility';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
    ];

    protected $nullable = [
        'start_date',
        'end_date',
    ];

    public function hasDateRange() {
        return $this->start_date != null || $this->end_date != null;
    }

    public function isInsideDateRange(Carbon $date)
    {
        return ($this->start_date == null || $date >= $this->start_date)
            && ($this->end_date == null || $date <= $this->end_date);
    }

    public function getStartDateStringAttribute() {
        return $this->start_date != null ? $this->start_date->toDateString() : null;
    }

    public function getEndDateStringAttribute() {
        return $this->end_date != null ? $this->end_date->toDateString() : null;
    }

    public function getDateRangeStringAttribute() {
        $date_strings = [
            'from' => $this->start_date_string,
            'until' => $this->end_date_string,
        ];
        if ($date_strings['from'] != null && $date_strings['until'] != null) {
            return __('app.from_until_date', $date_strings);
        } else if ($date_strings['from'] != null) {
            return __('app.from_date', $date_strings);
        } else {
            return __('app.until_date', $date_strings);
        }
    }
}
