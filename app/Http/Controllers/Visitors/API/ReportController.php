<?php

namespace App\Http\Controllers\Visitors\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Models\Visitors\Visitor;
use App\Models\Visitors\VisitorCheckin;
use App\Support\ChartResponseBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Setting;

class ReportController extends Controller
{
    use ValidatesDateRanges;

    public function dailyVisitors(Request $request): Collection
    {
        $this->authorize('viewAny', Visitor::class);

        $request->validate([
            'days' => [
                'nullable',
                'int',
                'min:1',
            ],
        ]);
        $maxNumberOfActiveDays = $request->input('days', 10);

        return VisitorCheckin::query()
            ->selectRaw('DATE(created_at) as day')
            ->addSelect(
                collect(Setting::get('visitors.purposes_of_visit', []))
                    ->mapWithKeys(fn ($t, $k) => [
                        $t => VisitorCheckin::selectRaw('COUNT(*)')
                            ->whereRaw('DATE(created_at) = day')
                            ->where('purpose_of_visit', $t),
                    ])
                    ->toArray()
            )
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('day', 'desc')
            ->limit($maxNumberOfActiveDays)
            ->get();
    }

    public function monthlyVisitors(): Collection
    {
        $this->authorize('viewAny', Visitor::class);

        return VisitorCheckin::query()
            ->selectRaw('MONTH(created_at) as month')
            ->selectRaw('YEAR(created_at) as year')
            ->addSelect(
                collect(Setting::get('visitors.purposes_of_visit', []))
                    ->mapWithKeys(fn ($t, $k) => [
                        $t => VisitorCheckin::selectRaw('COUNT(*)')
                            ->whereRaw('MONTH(created_at) = month')
                            ->whereRaw('YEAR(created_at) = year')
                            ->where('purpose_of_visit', $t),
                    ])
                    ->toArray()
            )
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw('MONTH(created_at)')
            ->groupByRaw('YEAR(created_at)')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    public function dailyRegistrations(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Visitor::class);

        $this->validateDateGranularity($request);
        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $registrations = Visitor::inDateRange($dateFrom, $dateTo)
            ->groupByDateGranularity($request->input('granularity'))
            ->selectRaw('COUNT(*) AS `aggregated_value`')
            ->get()
            ->pluck('aggregated_value', 'date_label');

        return (new ChartResponseBuilder())
            ->dataset(__('Registrations'), $registrations)
            ->build();
    }

    public function ageDistribution(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Visitor::class);

        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $visitors = Visitor::inDateRange($dateFrom, $dateTo)
            ->fromSub(function ($query) {
                $query
                    ->selectRaw('COUNT(*) AS `total_visitors`, created_at')
                    ->selectRaw('YEAR(CURRENT_DATE()) - YEAR(date_of_birth) - (RIGHT(CURRENT_DATE(), 5) < RIGHT(date_of_birth, 5)) AS `age`')
                    ->from('visitors')
                    ->whereNotNull('date_of_birth')
                    ->groupBy('age');
            }, 'sub')
            ->selectRaw('CASE
                WHEN age < 18 THEN "Under 18"
                WHEN age >= 18 AND age < 30 THEN "18-29"
                WHEN age >= 30 AND age < 65 THEN "30-64"
                WHEN age >= 65 THEN "65 and above"
            END AS `age_group`')
            ->selectRaw('COUNT(*) AS `total_visitors`')
            ->groupBy('age_group')
            ->orderByRaw("FIELD(age_group, 'Under 18', '18-29', '30-64', '65 and above')")
            ->get()
            ->pluck('total_visitors', 'age_group');
        
        return (new ChartResponseBuilder())
            ->dataset(__('Visitors'), $visitors, null, false)
            ->build();
    }

    public function nationalityDistribution(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Visitor::class);

        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $visitors = Visitor::inDateRange($dateFrom, $dateTo)
            ->selectRaw('nationality, COUNT(*) AS `total_visitors`')
            ->whereNotNull('nationality')
            ->groupBy('nationality')
            ->orderByDesc('total_visitors', 'INT')
            ->get()
            ->pluck('total_visitors', 'nationality');

        return (new ChartResponseBuilder())
            ->dataset(__('Visitors'), $visitors, null, false)
            ->build();
    }

    public function checkInsByVisitor(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Visitor::class);

        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $visits = VisitorCheckin::inDateRange($dateFrom, $dateTo)
            ->selectRaw('COUNT(*) AS `total_visits`')
            ->groupBy('visitor_id')
            ->get()
            ->groupBy('total_visits')
            ->map(function ($visitsGroup) {
                return $visitsGroup->count();
            });
            
        return (new ChartResponseBuilder())
            ->dataset(__('Visits'), $visits)
            ->build();
    }

    public function checkInsByPurpose(Request $request): JsonResponse
    {        
        $this->authorize('viewAny', Visitor::class);

        $this->validateDateGranularity($request);
        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $checkins = VisitorCheckin::inDateRange($dateFrom, $dateTo, 'created_at')
            ->groupByDateGranularity($request->input('granularity'), 'created_at')
            ->selectRaw('purpose_of_visit, COUNT(*) AS `total_checkins`')
            ->groupBy('purpose_of_visit');

        $purposes = $checkins->pluck('purpose_of_visit')->unique();

        $chartResponseBuilder = (new ChartResponseBuilder());

        foreach ($purposes as $purpose) {
            $purposeCheckins = $checkins->where('purpose_of_visit', $purpose);
            $title = $purpose ? $purpose : 'Unknown Purpose';
            $checkinCounts = $purposeCheckins->pluck('total_checkins', 'date_label');
            $chartResponseBuilder->dataset($title, $checkinCounts);
        }        

        return $chartResponseBuilder->build();
    }
}