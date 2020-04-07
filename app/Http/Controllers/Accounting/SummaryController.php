<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MoneyTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request)
    {
        $this->authorize('view-accounting-summary');

        $currentMonth = Carbon::now()->startOfMonth();

        $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:' . Carbon::today()->year,
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
        session([
            'accounting.summary_range.year' => $year,
            'accounting.summary_range.month' => $month,
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
            $heading = __('app.all_time');
            $currentRange = null;
        }

        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        $revenueByCategory = MoneyTransaction::revenueByField('category', $dateFrom, $dateTo);
        $revenueByProject = MoneyTransaction::revenueByField('project', $dateFrom, $dateTo);
        $wallet = MoneyTransaction::currentWallet($dateTo);

        $spending = MoneyTransaction::totalSpending($dateFrom, $dateTo);
        $income = MoneyTransaction::totalIncome($dateFrom, $dateTo);

        $months = MoneyTransaction::selectRaw('MONTH(date) as month')
            ->selectRaw('YEAR(date) as year')
            ->groupByRaw('MONTH(date)')
            ->groupByRaw('YEAR(date)')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->mapWithKeys(fn ($e) => self::toYearMonthMap($e->year, $e->month))
            ->prepend($currentMonth->formatLocalized('%B %Y'), $currentMonth->format('Y-m'))
            ->toArray();

        $years = MoneyTransaction::selectRaw('YEAR(date) as year')
            ->groupByRaw('YEAR(date)')
            ->orderBy('year', 'desc')
            ->get()
            ->mapWithKeys(fn ($e) => [ $e->year => $e->year ])
            ->prepend($currentMonth->format('Y'), $currentMonth->format('Y'))
            ->toArray();

        return view('accounting.transactions.summary', [
            'heading' => $heading,
            'currentRange' => $currentRange,
            'months' => $months,
            'years' => $years,
            'revenueByCategory' => $revenueByCategory,
            'revenueByProject' => $revenueByProject,
            'wallet' => $wallet,
            'spending' => $spending,
            'income' => $income,
            'filterDateStart' => optional($dateFrom)->toDateString(),
            'filterDateEnd' => optional($dateTo)->toDateString(),
        ]);
    }

    private static function toYearMonthMap(int $year, int $month)
    {
        $date = new Carbon($year.'-'.$month.'-01');
        return [ $date->format('Y-m') => $date->formatLocalized('%B %Y') ];
    }
}
