<?php

namespace App\Models\CommunityVolunteers;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

class CommunityVolunteerResponsibility extends Pivot
{
    use NullableFields;

    protected $table = 'community_volunteers_responsibility';

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    protected $nullable = [
        'start_date',
        'end_date',
    ];

    public function hasDateRange(): bool
    {
        return $this->start_date != null || $this->end_date != null;
    }

    public function isInsideDateRange(Carbon $date): bool
    {
        return ($this->start_date == null || $date >= $this->start_date)
            && ($this->end_date == null || $date <= $this->end_date);
    }

    /**
     * @return Attribute<?string,never>
     */
    protected function startDateString(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->start_date?->toDateString();
            },
        );
    }

    /**
     * @return Attribute<?string,never>
     */
    protected function endDateString(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->end_date?->toDateString();
            },
        );
    }

    /**
     * @return Attribute<string,never>
     */
    protected function dateRangeString(): Attribute
    {
        return Attribute::make(
            get: function () {
                $date_strings = [
                    'from' => $this->startDateString,
                    'until' => $this->endDateString,
                ];

                if ($date_strings['from'] != null && $date_strings['until'] != null) {
                    return __(':from - :until', $date_strings);
                }

                if ($date_strings['from'] != null) {
                    return __('from :from', $date_strings);
                }

                return __('until :until', $date_strings);
            },
        );
    }
}
