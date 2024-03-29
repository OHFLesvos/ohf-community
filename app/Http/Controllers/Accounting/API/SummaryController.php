<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Category;
use App\Models\Accounting\Project;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use App\Support\Accounting\FormatsCurrency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Setting;

class SummaryController extends Controller
{
    use FormatsCurrency;

    public function index(Request $request): JsonResponse
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
                'max:'.today()->year,
            ],
            'wallet' => [
                'nullable',
                'exists:accounting_wallets,id',
            ],
            'category' => [
                'nullable',
                'exists:accounting_categories,id',
            ],
            'project' => [
                'nullable',
                'exists:accounting_projects,id',
            ],
            'secondary_category' => [
                'nullable',
                'exists:accounting_transactions,secondary_category',
            ],
            'location' => [
                'nullable',
                'exists:accounting_transactions,location',
            ],
        ]);

        [$dateFrom, $dateTo] = $this->dateRange($request);

        $totals = $this->totals($request);
        $wallets = Wallet::orderBy('name')
            ->when($request->filled('wallet'), fn ($qry) => $qry->where('id', $request->wallet))
            ->get()
            ->filter(fn ($wallet) => request()->user()->can('view', $wallet))
            ->map(function (Wallet $wallet) use ($dateTo, $totals) {
                $income = isset($totals[$wallet->id]) ? $totals[$wallet->id]['income'] ?? 0 : 0;
                $spending = isset($totals[$wallet->id]) ? $totals[$wallet->id]['spending'] ?? 0 : 0;
                $amount = $wallet->calculatedSum($dateTo);
                $fees = isset($totals[$wallet->id]) ? $totals[$wallet->id]['fees'] ?? 0 : 0;

                return [
                    'id' => $wallet->id,
                    'name' => $wallet->name,
                    'income' => $income,
                    'income_formatted' => $this->formatCurrency($income),
                    'spending' => $spending,
                    'spending_formatted' => $this->formatCurrency($spending),
                    'difference' => $income - $spending,
                    'difference_formatted' => $this->formatCurrency($income - $spending),
                    'fees' => $fees,
                    'fees_formatted' => $this->formatCurrency($fees),
                    'amount' => $amount,
                    'amount_formatted' => $this->formatCurrency($amount),
                ];
            });

        $categories = Category::queryByParent();
        $revenueByCategory = $this->revenueByRelationField('category_id', 'category', $request);
        $this->fillInRevenue($categories, $revenueByCategory);
        $categories = $this->removeZeroRevenueItems($categories);
        $categories = $this->addFormattedAmounts($categories);

        $projects = Project::queryByParent();
        $revenueByProject = $this->revenueByRelationField('project_id', 'project', $request);
        $this->fillInRevenue($projects, $revenueByProject);
        $projects = $this->removeZeroRevenueItems($projects);
        $projects = $this->addFormattedAmounts($projects);

        $useLocations = Setting::get('accounting.transactions.use_locations') ?? false;
        $useSecondaryCategories = Setting::get('accounting.transactions.use_secondary_categories') ?? false;

        $totalIncome = $totals->sum('income');
        $totalSpending = $totals->sum('spending');
        $totalFees = $totals->sum('fees');
        $totalAmount = $wallets->sum('amount');

        return response()
            ->json([
                'years' => Transaction::years(),
                'categories' => $categories,
                'projects' => $projects,
                'wallets' => $wallets,
                'totals' => [
                    'income' => $totalIncome,
                    'income_formatted' => $this->formatCurrency($totalIncome),
                    'spending' => $totalSpending,
                    'spending_formatted' => $this->formatCurrency($totalSpending),
                    'difference' => $totalIncome - $totalSpending,
                    'difference_formatted' => $this->formatCurrency($totalIncome - $totalSpending),
                    'fees' => $totalFees,
                    'fees_formatted' => $this->formatCurrency($totalFees),
                    'amount' => $totalAmount,
                    'amount_formatted' => $this->formatCurrency($totalAmount),
                ],
                'locations' => $useLocations
                    ? $this->revenueByField('location', $request)
                    : null,
                'second_categories' => $useSecondaryCategories
                    ? $this->revenueByField('secondary_category', $request)
                    : null,
                'use_locations' => $useLocations,
            ]);
    }

    private function totals(Request $request): Collection
    {
        return Transaction::query()
            ->select('wallet_id')
            ->selectRaw('SUM(IF(type = \'income\', amount, 0)) as income_sum')
            ->selectRaw('SUM(IF(type = \'spending\', amount, 0)) as spending_sum')
            ->selectRaw('SUM(fees) as fees_sum')
            ->where(fn ($q) => $this->filterQuery($request, $q))
            ->groupBy('wallet_id')
            ->get()
            ->when($request->user() != null, fn ($q) => $q->filter(fn ($e) => $request->user()->can('view', $e->wallet)))
            ->mapWithKeys(fn ($e) => [$e->wallet_id => [
                'income' => $e->income_sum,
                'spending' => $e->spending_sum,
                'fees' => $e->fees_sum,
            ]]);
    }

    private function fillInRevenue(Collection $items, Collection $revenues): float
    {
        $total = 0;
        foreach ($items as &$item) {
            $childRevenue = $this->fillInRevenue($item['children'], $revenues);
            $item['amount'] = (float) $revenues->get($item['id'], 0);
            $item['total_amount'] = $item['amount'] + $childRevenue;
            $total += $item['total_amount'];
        }

        return $total;
    }

    private function removeZeroRevenueItems(Collection $items): Collection
    {
        $filteredItems = [];
        foreach ($items as &$item) {
            if ($item['total_amount'] != 0) {
                $item['children'] = $this->removeZeroRevenueItems($item['children']);
                $filteredItems[] = $item;
            }
        }

        return collect($filteredItems);
    }

    private function addFormattedAmounts(Collection $items): Collection
    {
        foreach ($items as &$item) {
            $item['children'] = $this->addFormattedAmounts($item['children']);
            $item['amount_formatted'] = $this->formatCurrency($item['amount']);
            $item['total_amount_formatted'] = $this->formatCurrency($item['total_amount']);
        }

        return $items;
    }

    private function dateRange(Request $request): array
    {
        if ($request->filled('year') && $request->filled('month')) {
            $dateFrom = (new Carbon($request->year.'-'.$request->month.'-01'))->startOfMonth();
            $dateTo = (clone $dateFrom)->endOfMonth();
        } elseif ($request->filled('year')) {
            $dateFrom = (new Carbon($request->year.'-01-01'))->startOfYear();
            $dateTo = (clone $dateFrom)->endOfYear();
        } else {
            $dateFrom = null;
            $dateTo = null;
        }

        return [$dateFrom, $dateTo];
    }

    /**
     * @param  Builder<Transaction>  $query
     */
    private function filterQuery(Request $request, $query): Builder
    {
        [$dateFrom, $dateTo] = $this->dateRange($request);
        $query->forDateRange($dateFrom, $dateTo);

        return $query
            ->when($request->filled('wallet'), fn (Builder $qry) => $qry->where('wallet_id', $request->wallet))
            ->when($request->filled('category'), fn (Builder $qry) => $qry->where('category_id', '=', $request->project))
            ->when($request->filled('project'), fn (Builder $qry) => $qry->where('project_id', '=', $request->project))
            ->when($request->filled('location'), fn (Builder $qry) => $qry->where('location', '=', $request->location))
            ->when($request->filled('secondary_category'), fn (Builder $qry) => $qry->where('secondary_category', '=', $request->secondary_category));
    }

    private function revenueByRelationField(string $idField, $relationField, Request $request): Collection
    {
        return Transaction::query()
            ->select($idField, 'wallet_id')
            ->selectRaw('SUM(IF(type = \'income\', amount, -1 * amount)) as sum')
            ->where(fn ($q) => $this->filterQuery($request, $q))
            ->groupBy($idField)
            ->orderBy($idField)
            ->get()
            ->when($request->user() != null, fn ($q) => $q->filter(fn ($e) => $request->user()->can('view', $e->wallet)))
            ->pluck('sum', $idField);
    }

    private function revenueByField(string $field, Request $request): Collection
    {
        return Transaction::query()
            ->select($field, 'wallet_id')
            ->selectRaw('SUM(IF(type = \'income\', amount, -1 * amount)) as sum')
            ->where(fn ($q) => $this->filterQuery($request, $q))
            ->groupBy($field)
            ->orderBy($field)
            ->get()
            ->when($request->user() != null, fn ($q) => $q->filter(fn ($e) => $request->user()->can('view', $e->wallet)))
            ->map(fn ($e) => [
                'name' => $e->$field,
                'amount' => $e->sum,
                'wallet_id' => $request->wallet != null ? $e->wallet_id : null,
            ])
            ->values();
    }
}
