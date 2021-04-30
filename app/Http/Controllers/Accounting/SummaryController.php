<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Project;
use App\Models\Accounting\SignedMoneyTransaction;
use App\Models\Accounting\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Setting;

class SummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function summary(Wallet $wallet, Request $request)
    {
        $this->authorize('view-accounting-summary');

        setlocale(LC_TIME, \App::getLocale());

        $currentMonth = Carbon::now()->startOfMonth();

        $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:' . Carbon::today()->year,
        ]);

        if ($wallet === null) {
            return redirect()->route('accounting.wallets.change');
        }

        if ($request->filled('year') && $request->filled('month')) {
            $year = $request->year;
            $month = $request->month;
        } elseif ($request->filled('year')) {
            $year = $request->year;
            $month = null;
        } elseif ($request->has('year')) {
            $year = null;
            $month = null;
        } elseif ($request->session()->has('accounting.summary_range.year') && $request->session()->has('accounting.summary_range.month')) {
            $year = $request->session()->get('accounting.summary_range.year');
            $month = $request->session()->get('accounting.summary_range.month');
        } elseif ($request->session()->has('accounting.summary_range.year')) {
            $year = $request->session()->get('accounting.summary_range.year');
            $month = null;
        } elseif ($request->session()->exists('accounting.summary_range.year')) {
            $year = null;
            $month = null;
        } else {
            $year = $currentMonth->year;
            $month = $currentMonth->month;
        }
        if ($request->filled('project')) {
            $project = $request->project;
        } elseif ($request->has('project')) {
            $project = null;
        } elseif ($request->session()->has('accounting.summary_range.project')) {
            $project = $request->session()->get('accounting.summary_range.project');
        } else {
            $project = null;
        }
        if ($request->filled('location')) {
            $location = $request->location;
        } elseif ($request->has('location')) {
            $location = null;
        } elseif ($request->session()->has('accounting.summary_range.location')) {
            $location = $request->session()->get('accounting.summary_range.location');
        } else {
            $location = null;
        }
        session([
            'accounting.summary_range.year' => $year,
            'accounting.summary_range.month' => $month,
            'accounting.summary_range.project' => $project,
            'accounting.summary_range.location' => $location,
        ]);

        if ($year != null && $month != null) {
            $dateFrom = (new Carbon($year.'-'.$month.'-01'))->startOfMonth();
            $dateTo = (clone $dateFrom)->endOfMonth();
            $heading = $dateFrom->formatLocalized('%B %Y');
            $currentRange = $dateFrom->format('Y-m');
        } elseif ($year != null) {
            $dateFrom = (new Carbon($year.'-01-01'))->startOfYear();
            $dateTo = (clone $dateFrom)->endOfYear();
            $heading = $year;
            $currentRange = $year;
        } else {
            $dateFrom = null;
            $dateTo = null;
            $heading = __('All time');
            $currentRange = null;
        }

        $filters = [];
        if ($project != null) {
            array_push($filters, ['project', '=', $project]);
        }
        if ($location != null) {
            array_push($filters, ['location', '=', $location]);
        }

        $revenueByCategory = self::revenueByField('category_id', 'category', $wallet, $dateFrom, $dateTo, $filters);
        $revenueByProject = self::revenueByField('project_id', 'project', $wallet, $dateFrom, $dateTo, $filters);
        if (self::useSecondaryCategories()) {
            $revenueBySecondaryCategory = self::revenueByField2('secondary_category', $wallet, $dateFrom, $dateTo, $filters);
        } else {
            $revenueBySecondaryCategory = null;
        }

        $spending = self::totalByType('spending', $wallet, $dateFrom, $dateTo, $filters);
        $income = self::totalByType('income', $wallet, $dateFrom, $dateTo, $filters);
        $fees = self::totalFees($wallet, $dateFrom, $dateTo, $filters);

        $months = MoneyTransaction::query()
            ->selectRaw('MONTH(date) as month')
            ->selectRaw('YEAR(date) as year')
            ->forWallet($wallet)
            ->groupByRaw('MONTH(date)')
            ->groupByRaw('YEAR(date)')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->mapWithKeys(fn ($e) => self::toYearMonthMap($e->year, $e->month))
            ->prepend($currentMonth->formatLocalized('%B %Y'), $currentMonth->format('Y-m'))
            ->toArray();

        $years = MoneyTransaction::query()
            ->selectRaw('YEAR(date) as year')
            ->forWallet($wallet)
            ->groupByRaw('YEAR(date)')
            ->orderBy('year', 'desc')
            ->get()
            ->mapWithKeys(fn ($e) => [ $e->year => $e->year ])
            ->prepend($currentMonth->format('Y'), $currentMonth->format('Y'))
            ->toArray();

        return view('accounting.transactions.summary', [
            'heading' => $heading,
            'currentRange' => $currentRange,
            'currentProject' => $project,
            'currentLocation' => $location,
            'months' => $months,
            'years' => $years,
            'projects' => self::getProjects(),
            'locations' => self::useLocations() ? self::getLocations(true) : [],
            'revenueByCategory' => $revenueByCategory,
            'revenueByProject' => $revenueByProject,
            'revenueBySecondaryCategory' => $revenueBySecondaryCategory,
            'wallet_amount' => $wallet->calculatedSum($dateTo),
            'spending' => $spending,
            'income' => $income,
            'fees' => $fees,
            'filterDateStart' => optional($dateFrom)->toDateString(),
            'filterDateEnd' => optional($dateTo)->toDateString(),
            'wallet' => $wallet,
            'has_multiple_wallets' => Wallet::count() > 1,
        ]);
    }

    private static function toYearMonthMap(int $year, int $month)
    {
        $date = new Carbon($year.'-'.$month.'-01');
        return [ $date->format('Y-m') => $date->formatLocalized('%B %Y') ];
    }

    private static function revenueByField(string $idField, string $relationField, Wallet $wallet, ?Carbon $dateFrom = null, ?Carbon $dateTo = null): Collection
    {
        return SignedMoneyTransaction::query()
            ->select($idField)
            ->selectRaw('SUM(amount) as sum')
            ->forWallet($wallet)
            ->forDateRange($dateFrom, $dateTo)
            ->groupBy($idField)
            ->orderBy($idField)
            ->get()
            ->map(fn ($e) => [
                'id' => $e->$idField,
                'name' => optional($e->$relationField)->name,
                'amount' => $e->sum,
            ]);
    }

    private static function revenueByField2(string $field, Wallet $wallet, ?Carbon $dateFrom = null, ?Carbon $dateTo = null): Collection
    {
        return MoneyTransaction::query()
            ->select($field)
            ->selectRaw('SUM(IF(type = \'income\', amount, -1*amount)) as sum')
            ->forWallet($wallet)
            ->forDateRange($dateFrom, $dateTo)
            ->groupBy($field)
            ->orderBy($field)
            ->get()
            ->map(fn ($e) => [
                'name' => $e->$field,
                'amount' => $e->sum,
            ]);
    }

    private static function totalByType(string $type, Wallet $wallet, ?Carbon $dateFrom = null, ?Carbon $dateTo = null): ?float
    {
        $result = MoneyTransaction::query()
            ->selectRaw('SUM(amount) as sum')
            ->forWallet($wallet)
            ->forDateRange($dateFrom, $dateTo)
            ->where('type', $type)
            ->first();

        return optional($result)->sum;
    }

    private static function totalFees(Wallet $wallet, ?Carbon $dateFrom = null, ?Carbon $dateTo = null): ?float
    {
        $result = MoneyTransaction::query()
            ->selectRaw('SUM(fees) as sum')
            ->forWallet($wallet)
            ->forDateRange($dateFrom, $dateTo)
            ->first();

        return optional($result)->sum;
    }

    private static function useSecondaryCategories(): bool
    {
        return Setting::get('accounting.transactions.use_secondary_categories') ?? false;
    }

    private static function getProjects(): Collection
    {
        return Project::orderBy('name')
            ->pluck('name', 'id');
    }

    private static function useLocations(): bool
    {
        return Setting::get('accounting.transactions.use_locations') ?? false;
    }

    private static function getLocations(?bool $onlyExisting = false): array
    {
        if (! $onlyExisting && Setting::has('accounting.transactions.locations')) {
            return collect(Setting::get('accounting.transactions.locations'))
                ->sort()
                ->toArray();
        }
        return MoneyTransaction::locations();
    }
}
