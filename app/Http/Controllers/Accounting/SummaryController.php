<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Project;
use App\Models\Accounting\Wallet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Setting;

class SummaryController extends Controller
{
    public function index(?Wallet $wallet = null, Request $request)
    {
        $this->authorize('view-accounting-summary');

        setlocale(LC_TIME, app()->getLocale());

        $currentMonth = now()->startOfMonth();

        $request->validate([
            'month' => [
                'nullable',
                'integer',
                'min:1',
                'max:12',
            ],
            'year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:' . today()->year,
            ],
        ]);

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
            $dateFrom = (new Carbon($year . '-' . $month . '-01'))->startOfMonth();
            $dateTo = (clone $dateFrom)->endOfMonth();
            $heading = $dateFrom->formatLocalized('%B %Y');
            $currentRange = $dateFrom->format('Y-m');
        } elseif ($year != null) {
            $dateFrom = (new Carbon($year . '-01-01'))->startOfYear();
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
            array_push($filters, ['project_id', '=', $project]);
        }
        if ($location != null) {
            array_push($filters, ['location', '=', $location]);
        }

        $revenueByCategory = self::revenueByRelationField('category_id', 'category', $wallet, $dateFrom, $dateTo, $request->user(), $filters);
        $revenueByProject = self::revenueByRelationField('project_id', 'project', $wallet, $dateFrom, $dateTo, $request->user(), $filters);
        if (self::useSecondaryCategories()) {
            $revenueBySecondaryCategory = self::revenueByField('secondary_category', $wallet, $dateFrom, $dateTo, $request->user(), $filters);
        } else {
            $revenueBySecondaryCategory = null;
        }

        $spendingByWallet = self::totalByType('spending', $wallet, $dateFrom, $dateTo, $request->user(), $filters)
            ->pluck('sum', 'wallet_id');
        $incomeByWallet = self::totalByType('income', $wallet, $dateFrom, $dateTo, $request->user(), $filters)
            ->pluck('sum', 'wallet_id');
        $feesByWallet = self::totalFees($wallet, $dateFrom, $dateTo, $request->user(), $filters)
            ->pluck('sum', 'wallet_id');

        $spending = $spendingByWallet->sum();
        $income = $incomeByWallet->sum();
        $fees = $feesByWallet->sum();

        $months = MoneyTransaction::query()
            ->selectRaw('MONTH(date) as month')
            ->selectRaw('YEAR(date) as year')
            ->when($wallet != null, fn ($q) => $q->forWallet($wallet))
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
            ->when($wallet != null, fn ($q) => $q->forWallet($wallet))
            ->groupByRaw('YEAR(date)')
            ->orderBy('year', 'desc')
            ->get()
            ->mapWithKeys(fn ($e) => [$e->year => $e->year])
            ->prepend($currentMonth->format('Y'), $currentMonth->format('Y'))
            ->toArray();

        if ($wallet == null) {
            $wallets = Wallet::all()
                ->filter(fn ($w) => request()->user()->can('view', $w))
                ->map(fn ($w) => [
                    'wallet' => $w,
                    'income' => isset($incomeByWallet[$w->id]) ? $incomeByWallet[$w->id] : 0,
                    'spending' => isset($spendingByWallet[$w->id]) ? $spendingByWallet[$w->id] : 0,
                    'fees' => isset($feesByWallet[$w->id]) ? $feesByWallet[$w->id] : 0,
                    'amount' => $w->calculatedSum($dateTo),
                ]);
            $wallet_amount = $wallets->sum('amount');
        } else {
            $wallet_amount = $wallet->calculatedSum($dateTo);
        }

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
            'wallet_amount' => $wallet_amount,
            'spending' => $spending,
            'income' => $income,
            'fees' => $fees,
            'filterDateStart' => optional($dateFrom)->toDateString(),
            'filterDateEnd' => optional($dateTo)->toDateString(),
            'wallet' => $wallet,
            'wallets' => $wallets ?? null,
        ]);
    }

    private static function toYearMonthMap(int $year, int $month)
    {
        $date = new Carbon($year . '-' . $month . '-01');
        return [$date->format('Y-m') => $date->formatLocalized('%B %Y')];
    }

    private static function revenueByRelationField(string $idField, $relationField, ?Wallet $wallet, ?Carbon $dateFrom = null, ?Carbon $dateTo = null, ?User $user = null, ?array $filters = []): Collection
    {
        return MoneyTransaction::query()
            ->select($idField, 'wallet_id')
            ->selectRaw('SUM(IF(type = \'income\', amount, -1*amount)) as sum')
            ->when($wallet != null, fn ($q) => $q->forWallet($wallet))
            ->forDateRange($dateFrom, $dateTo)
            ->groupBy($idField)
            ->orderBy($idField)
            ->where($filters)
            ->get()
            ->when(
                $user != null,
                fn ($q) => $q->filter(
                    fn ($e) => $user->can('view', Wallet::find($e->wallet_id))
                )
            )
            ->map(fn ($e) => [
                'id' => $e->$idField,
                'name' => optional($e->$relationField)->name,
                'amount' => $e->sum,
                'wallet_id' => $e->wallet_id,
            ])
            ->sortBy('name');
    }

    private static function revenueByField(string $field, ?Wallet $wallet, ?Carbon $dateFrom = null, ?Carbon $dateTo = null, ?User $user = null, ?array $filters = []): Collection
    {
        return MoneyTransaction::query()
            ->select($field, 'wallet_id')
            ->selectRaw('SUM(IF(type = \'income\', amount, -1*amount)) as sum')
            ->when($wallet != null, fn ($q) => $q->forWallet($wallet))
            ->forDateRange($dateFrom, $dateTo)
            ->groupBy($field)
            ->orderBy($field)
            ->where($filters)
            ->get()
            ->when(
                $user != null,
                fn ($q) => $q->filter(
                    fn ($e) => $user->can('view', Wallet::find($e->wallet_id))
                )
            )
            ->map(fn ($e) => [
                'name' => $e->$field,
                'amount' => $e->sum,
                'wallet_id' => $e->wallet_id,
            ]);
    }

    private static function totalByType(string $type, ?Wallet $wallet, ?Carbon $dateFrom = null, ?Carbon $dateTo = null, ?User $user = null, ?array $filters = []): Collection
    {
        return MoneyTransaction::query()
            ->select('wallet_id')
            ->selectRaw('SUM(amount) as sum')
            ->when($wallet != null, fn ($q) => $q->forWallet($wallet), fn ($q) => $q->groupBy('wallet_id'))
            ->forDateRange($dateFrom, $dateTo)
            ->where('type', $type)
            ->where($filters)
            ->get()
            ->when(
                $user != null,
                fn ($q) => $q->filter(
                    fn ($e) => $user->can('view', Wallet::find($e['wallet_id']))
                )
            );
    }

    private static function totalFees(?Wallet $wallet, ?Carbon $dateFrom = null, ?Carbon $dateTo = null, ?User $user = null, ?array $filters = []): Collection
    {
        return MoneyTransaction::query()
            ->select('wallet_id')
            ->selectRaw('SUM(fees) as sum')
            ->when($wallet != null, fn ($q) => $q->forWallet($wallet), fn ($q) => $q->groupBy('wallet_id'))
            ->forDateRange($dateFrom, $dateTo)
            ->where($filters)
            ->get()
            ->when(
                $user != null,
                fn ($q) => $q->filter(
                    fn ($e) => $user->can('view', Wallet::find($e['wallet_id']))
                )
            );
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
        if (!$onlyExisting && Setting::has('accounting.transactions.locations')) {
            return collect(Setting::get('accounting.transactions.locations'))
                ->sort()
                ->toArray();
        }
        return MoneyTransaction::locations();
    }
}
