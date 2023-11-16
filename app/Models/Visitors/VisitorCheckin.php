<?php

namespace App\Models\Visitors;

use App\Models\Traits\InDateRangeScope;
use Carbon\Carbon;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * @property-read ?int $total_count
 */
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

    protected $casts = [
        'checkin_date' => 'datetime:Y-m-d',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public static function createForDate(string|Carbon $date, string $purpose = null): VisitorCheckin
    {
        $checkin = new VisitorCheckin();
        $checkin->checkin_date = $date;
        $checkin->purpose_of_visit = $purpose;

        return $checkin;
    }

    public static function getPurposeList(): Collection
    {
        return VisitorCheckin::query()
            ->distinct('purpose_of_visit')
            ->whereNotNull('purpose_of_visit')
            ->orderBy('purpose_of_visit')
            ->pluck('purpose_of_visit');
    }
}
