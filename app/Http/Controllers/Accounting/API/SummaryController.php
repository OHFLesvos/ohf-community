<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Wallet;
use App\Models\User;
use App\Support\Accounting\TaxonomyRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Setting;

class SummaryController extends Controller
{
    public function index(Request $request, TaxonomyRepository $taxonomies)
    {
        $this->authorize('view-accounting-summary');

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
            'wallet' => [
                'nullable',
                'exists:accounting_wallets,id'
            ],
            'project' => [
                'nullable',
                'exists:accounting_projects,id'
            ],
            'location' => [
                'nullable',
                'exists:money_transactions,location'
            ],
        ]);

        if ($request->filled('year') && $request->filled('month')) {
            $year = $request->year;
            $month = $request->month;
        } elseif ($request->filled('year')) {
            $year = $request->year;
            $month = null;
        } else {
            $year = null;
            $month = null;
        }

        if ($year != null && $month != null) {
            $dateFrom = (new Carbon($year . '-' . $month . '-01'))->startOfMonth();
            $dateTo = (clone $dateFrom)->endOfMonth();
        } elseif ($year != null) {
            $dateFrom = (new Carbon($year . '-01-01'))->startOfYear();
            $dateTo = (clone $dateFrom)->endOfYear();
        } else {
            $dateFrom = null;
            $dateTo = null;
        }

        $filters = [];

        if ($request->filled('project')) {
            array_push($filters, ['project_id', '=', $request->project]);
        }

        if ($request->filled('location')) {
            array_push($filters, ['location', '=', $request->location]);
        }

        $wallet = Wallet::find($request->wallet);

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

        $years = MoneyTransaction::query()
            ->selectRaw('YEAR(date) as year')
            ->when($wallet != null, fn ($q) => $q->forWallet($wallet))
            ->groupByRaw('YEAR(date)')
            ->orderBy('year', 'desc')
            ->get()
            ->pluck('year')
            ->unique()
            ->toArray();

        $wallets = Wallet::all()
            ->filter(fn ($wallet) => request()->user()->can('view', $wallet))
            ->map(fn ($wallet) => [
                'id' => $wallet->id,
                'name' => $wallet->name,
                'income' => isset($incomeByWallet[$wallet->id]) ? $incomeByWallet[$wallet->id] : 0,
                'spending' => isset($spendingByWallet[$wallet->id]) ? $spendingByWallet[$wallet->id] : 0,
                'fees' => isset($feesByWallet[$wallet->id]) ? $feesByWallet[$wallet->id] : 0,
                'amount' => $wallet->calculatedSum($dateTo),
            ]);
        if ($wallet == null) {
            $wallet_amount = $wallets->sum('amount');
        } else {
            $wallet_amount = $wallet->calculatedSum($dateTo);
        }

        return [
            'years' => $years,
            'projects' => collect($taxonomies->getNestedProjects())->map(fn ($label, $id) =>  [
                "id" => $id,
                "label" => $label,
            ])->values(),
            'locations' => self::useLocations() ? self::getLocations(true) : [],
            'revenueByCategory' => $revenueByCategory,
            'revenueByProject' => $revenueByProject,
            'revenueBySecondaryCategory' => $revenueBySecondaryCategory,
            'wallet_amount' => $wallet_amount,
            'spending' => $spending,
            'income' => $income,
            'fees' => $fees,
            'wallets' => $wallets,
        ];
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
            ->sortBy('name')
            ->values();
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
            ])
            ->values();
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
