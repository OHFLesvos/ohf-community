<?php

namespace App\Http\Controllers\Visitors\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Models\Visitors\Visitor;
use App\Models\Visitors\VisitorCheckin;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    use ValidatesDateRanges;

    public function visitorCheckins(Request $request, Connection $connection)
    {
        $this->authorize('view-visitors-reports');

        $request->validate([
            'date_start' => [
                'nullable',
                'date',
                'before_or_equal:date_end',
            ],
            'date_end' => [
                'nullable',
                'date',
                'after_or_equal:date_start',
            ],
            'granularity' => [
                'nullable',
                Rule::in(['years', 'months', 'weeks', 'days']),
            ],
            'purpose' => [
                'nullable',
                'string',
            ],
        ]);

        return response()->json([
            'data' => $this->getVisitorCheckinData($request),
        ]);
    }

    private function getVisitorCheckinData(Request $request)
    {
        if (! VisitorCheckin::query()->exists()) {
            return [];
        }

        $this->createCalendarTempTable($request);

        $vcQuery = VisitorCheckin::query()
            ->select('checkin_date')
            ->when($request->filled('purpose'), fn ($qry) => $qry->where('purpose_of_visit', $request->input('purpose')))
            ->selectRaw('count(checkin_date) as checkin_date_count')
            ->groupBy('checkin_date');

        return DB::table('calendar')
            ->when(true, fn ($qry) => $this->selectByDateGranularity($qry, $request->input('granularity'), 'calendar_date', 'checkin_date_range'))
            ->selectRaw('CAST(SUM(CASE WHEN vc.checkin_date_count is NULL THEN 0 ELSE vc.checkin_date_count END) as UNSIGNED) AS checkin_count')
            ->leftJoinSub($vcQuery, 'vc', function ($join) {
                $join->on('vc.checkin_date', '=', 'calendar_date');
            })
            ->when($request->has('date_start'), fn ($qry) => $qry->whereDate('calendar_date', '>=', $request->input('date_start')))
            ->when($request->has('date_end'), fn ($qry) => $qry->whereDate('calendar_date', '<=', $request->input('date_end')))
            ->groupBy('checkin_date_range')
            ->orderBy('calendar_date', 'desc')
            ->get();
    }

    private function createCalendarTempTable(Request $request)
    {
        DB::statement('CREATE TEMPORARY TABLE `calendar` (
            `calendar_date` date NOT NULL
        )');
        DB::statement('CALL FillCalendar(:date_start, :date_end);', [
            'date_start' => $request->input('date_start', VisitorCheckin::query()->orderBy('checkin_date', 'asc')->limit(1)->first()->checkin_date),
            'date_end' => $request->input('date_end', today()),
        ]);
    }

    private function selectByDateGranularity($qry, ?string $granularity = 'days', ?string $column = 'created_at', ?string $alias = 'date_label')
    {
        switch ($granularity) {
            case 'years':
                return $qry->selectRaw("YEAR(`{$column}`) as `{$alias}`");
            case 'months':
                return $qry->selectRaw("DATE_FORMAT(`{$column}`, '%Y-%m') as `{$alias}`");
            case 'weeks':
                return $qry->selectRaw("DATE_FORMAT(`{$column}`, '%x-W%v') as `{$alias}`");
            case 'days':
            default:
                return $qry->selectRaw("DATE(`{$column}`) as `{$alias}`");
        }
    }

    public function genderDistribution(Request $request): JsonResponse
    {
        $this->authorize('view-visitors-reports');

        [$startDate, $endDate] = $this->getDatePeriodFromRequest($request, defaultDays: null, dateStartField: 'date_start', dateEndField: 'date_end');

        $data = Visitor::query()
            ->select('gender')
            ->selectRaw('COUNT(*) AS `total_count`')
            ->whereHas('checkins', function (Builder $qry2) use ($startDate, $endDate, $request) {
                $qry2->when($startDate !== null, fn (Builder $q) => $q->whereDate('checkin_date', '>=', $startDate))
                    ->when($endDate !== null, fn (Builder $q) => $q->whereDate('checkin_date', '<=', $endDate))
                    ->when($request->filled('purpose'), fn ($qry) => $qry->where('purpose_of_visit', $request->input('purpose')));
            })
            ->groupBy('gender')
            ->orderBy('total_count', 'desc')
            ->orderBy('gender')
            ->get();

        return response()->json($data
            ->map(fn ($e) => [
                'label' => __($e->gender),
                'value' => $e->total_count,
            ]));
    }

    public function ageDistribution(Request $request): JsonResponse
    {
        $this->authorize('view-visitors-reports');

        [$startDate, $endDate] = $this->getDatePeriodFromRequest($request, defaultDays: null, dateStartField: 'date_start', dateEndField: 'date_end');

        $data = Visitor::query()
            ->selectRaw('CASE
                    WHEN age <= 5 THEN "1-5"
                    WHEN age >= 6 AND age <= 11 THEN "6-11"
                    WHEN age >= 12 AND age <= 17 THEN "12-17"
                    WHEN age >= 18 AND age <= 25 THEN "18-25"
                    WHEN age >= 26 AND age <= 35 THEN "26-35"
                    WHEN age >= 36 AND age <= 45 THEN "36-45"
                    WHEN age >= 46 AND age <= 55 THEN "46-55"
                    WHEN age >= 56 AND age <= 65 THEN "56-65"
                    ELSE "66+"
                END AS age_group')
            ->selectRaw('COUNT(*) as total_count')
            ->from(function ($query) use ($startDate, $endDate, $request) {
                $query->select('v.id')->selectRaw('TIMESTAMPDIFF(YEAR, v.date_of_birth, CURDATE()) AS age')
                    ->from('visitors as v')
                    ->join('visitor_checkins as vc', 'v.id', '=', 'vc.visitor_id')
                    ->whereBetween('vc.checkin_date', [$startDate, $endDate])
                    ->when($request->filled('purpose'), fn ($qry) => $qry->where('purpose_of_visit', $request->input('purpose')))
                    ->groupBy('v.id');
            }, 's')
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get()
            ->sortBy('age_group', SORT_NATURAL)->values();

        return response()->json($data
            ->map(fn ($e) => [
                'label' => __($e->age_group),
                'value' => $e->total_count,
            ]));
    }

    public function nationalityDistribution(Request $request): JsonResponse
    {
        $this->authorize('view-visitors-reports');

        [$startDate, $endDate] = $this->getDatePeriodFromRequest($request, defaultDays: null, dateStartField: 'date_start', dateEndField: 'date_end');

        $data = Visitor::query()
            ->select('nationality')
            ->selectRaw('COUNT(*) AS `total_count`')
            ->whereHas('checkins', function (Builder $qry2) use ($startDate, $endDate, $request) {
                $qry2->when($startDate !== null, fn (Builder $q) => $q->whereDate('checkin_date', '>=', $startDate))
                    ->when($endDate !== null, fn (Builder $q) => $q->whereDate('checkin_date', '<=', $endDate))
                    ->when($request->filled('purpose'), fn ($qry) => $qry->where('purpose_of_visit', $request->input('purpose')));
            })
            ->groupBy('nationality')
            ->orderBy('total_count', 'desc')
            ->orderBy('nationality')
            ->get();

        return response()->json($data
            ->map(fn ($e) => [
                'label' => __($e->nationality),
                'value' => $e->total_count,
            ]));
    }

    public function checkInsByPurpose(Request $request): JsonResponse
    {
        $this->authorize('view-visitors-reports');

        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request, dateStartField: 'date_start', dateEndField: 'date_end');

        $checkins = VisitorCheckin::query()
            ->inDateRange($dateFrom, $dateTo, 'checkin_date')
            ->selectRaw('purpose_of_visit')
            ->selectRaw('COUNT(*) as total_count')
            ->groupBy('purpose_of_visit')
            ->orderBy('total_count', 'desc')
            ->pluck('total_count', 'purpose_of_visit');

        return response()->json($checkins);
    }
}
