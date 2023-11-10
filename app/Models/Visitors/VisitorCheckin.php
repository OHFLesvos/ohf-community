<?php

namespace App\Models\Visitors;

use App\Models\Traits\InDateRangeScope;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorCheckin extends Model
{
    use HasFactory;
    use InDateRangeScope;
    use NullableFields;

    protected $fillable = [
        'purpose_of_visit',
    ];

    protected $nullable = [
        'purpose_of_visit',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public static function createForDate(string $date, string $purpose = null): VisitorCheckin
    {
        $checkin = new VisitorCheckin();
        $checkin->checkin_date = $date;
        $checkin->purpose_of_visit = $purpose;

        return $checkin;
    }
}
