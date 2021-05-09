<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Wallet;
use App\Support\Accounting\TaxonomyRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
                'min:2010',
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

        [$dateFrom, $dateTo] = $this->dateRange($request);

        $totals = $this->totals($request);

        $wallets = Wallet::orderBy('name')
            ->get()
            ->filter(fn ($wallet) => request()->user()->can('view', $wallet))
            ->map(fn ($wallet) => [
                'id' => $wallet->id,
                'name' => $wallet->name,
                'income' => isset($totals[$wallet->id]) ? $totals[$wallet->id]['income'] : 0,
                'spending' => isset($totals[$wallet->id]) ? $totals[$wallet->id]['spending'] : 0,
                'fees' => isset($totals[$wallet->id]) ? $totals[$wallet->id]['fees'] : 0,
                'amount' => $wallet->calculatedSum($dateTo),
            ]);

        $useLocations = Setting::get('accounting.transactions.use_locations') ?? false;
        $useSecondaryCategories = Setting::get('accounting.transactions.use_secondary_categories') ?? false;
        return [
            'years' => MoneyTransaction::years(),
            'projects' => collect($taxonomies->getNestedProjects())
                ->map(fn ($label, $id) =>  [
                    "id" => $id,
                    "label" => $label,
                ])
                ->values(),
            'locations' => $useLocations ? MoneyTransaction::locations() : [],
            'wallets' => $wallets,
            'totals' => [
                'income' => $totals->sum('income'),
                'spending' => $totals->sum('spending'),
                'fees' => $totals->sum('fees'),
                'amount' => $wallets->sum('amount'),
            ],
            'revenueByCategory' => $this->revenueByRelationField('category_id', 'category', $request),
            'revenueByProject' => $this->revenueByRelationField('project_id', 'project', $request),
            'revenueBySecondaryCategory' => $useSecondaryCategories
                ? $this->revenueByField('secondary_category', $request)
                : null,
        ];
    }

    private function dateRange(Request $request)
    {
        if ($request->filled('year') && $request->filled('month')) {
            $dateFrom = (new Carbon($request->year . '-' . $request->month . '-01'))->startOfMonth();
            $dateTo = (clone $dateFrom)->endOfMonth();
        } elseif ($request->filled('year')) {
            $dateFrom = (new Carbon($request->year . '-01-01'))->startOfYear();
            $dateTo = (clone $dateFrom)->endOfYear();
        } else {
            $dateFrom = null;
            $dateTo = null;
        }
        return [$dateFrom, $dateTo];
    }

    private function filterQuery(Request $request, Builder $query): Builder
    {
        [$dateFrom, $dateTo] = $this->dateRange($request);
        $query->forDateRange($dateFrom, $dateTo);

        if ($request->filled('project')) {
            $query->where('project_id', '=', $request->project);
        }

        if ($request->filled('location')) {
            $query->where('location', '=', $request->location);
        }

        $query->when($request->wallet != null, fn ($q) => $q->where('wallet_id', $request->wallet));

        return $query;
    }

    private function revenueByRelationField(string $idField, $relationField, Request $request): Collection
    {
        return MoneyTransaction::query()
            ->select($idField, 'wallet_id')
            ->selectRaw('SUM(IF(type = \'income\', amount, -1 * amount)) as sum')
            ->where(fn($q) => $this->filterQuery($request, $q))
            ->groupBy($idField)
            ->orderBy($idField)
            ->get()
            ->when($request->user() != null, fn ($q) => $q->filter(fn ($e) => $request->user()->can('view', $e->wallet)))
            ->map(fn ($e) => [
                'id' => $e->$idField,
                'name' => optional($e->$relationField)->name,
                'amount' => $e->sum,
                'wallet_id' => $e->wallet_id,
            ])
            ->sortBy('name')
            ->values();
    }

    private function revenueByField(string $field, Request $request): Collection
    {
        return MoneyTransaction::query()
            ->select($field, 'wallet_id')
            ->selectRaw('SUM(IF(type = \'income\', amount, -1 * amount)) as sum')
            ->where(fn($q) => $this->filterQuery($request, $q))
            ->groupBy($field)
            ->orderBy($field)
            ->get()
            ->when($request->user() != null, fn ($q) => $q->filter(fn ($e) => $request->user()->can('view', $e->wallet)))
            ->map(fn ($e) => [
                'name' => $e->$field,
                'amount' => $e->sum,
                'wallet_id' => $e->wallet_id,
            ])
            ->values();
    }

    private function totals(Request $request): Collection
    {
        return MoneyTransaction::query()
            ->select('wallet_id')
            ->selectRaw('SUM(IF(type = \'income\', amount, 0)) as income_sum')
            ->selectRaw('SUM(IF(type = \'spending\', amount, 0)) as spending_sum')
            ->selectRaw('SUM(fees) as fees_sum')
            ->where(fn($q) => $this->filterQuery($request, $q))
            ->groupBy('wallet_id')
            ->get()
            ->when($request->user() != null, fn ($q) => $q->filter(fn ($e) => $request->user()->can('view', $e->wallet)))
            ->mapWithKeys(fn($e) => [$e->wallet_id => [
                'income' => $e->income_sum,
                'spending' => $e->spending_sum,
                'fees' => $e->fees_sum,
            ]]);
    }
}
