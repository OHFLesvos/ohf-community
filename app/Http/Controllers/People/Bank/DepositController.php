<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\Bank\StoreDeposit;
use App\Project;
use App\CouponReturn;
use App\CouponType;
use Carbon\Carbon;

class DepositController extends Controller
{
    /**
     * Show view for storing a new deposit.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $projects = Project
                ::orderBy('name')
                ->where('enable_in_bank', true)
                ->get()
                ->mapWithKeys(function($e){
                    return [$e->id => $e->name];
                })
                ->toArray();

        $couponTypes = CouponType
                ::where('returnable', true)
                ->orderBy('order')
                ->orderBy('name')
                ->get()
                ->mapWithKeys(function($e){
                    return [$e->id => $e->name];
                })
                ->toArray();

        $todaysReturns = [];
        CouponReturn
                ::where('date', Carbon::today())
                ->orderBy('created_at', 'DESC')
                ->get()
                ->each(function($e) use(&$todaysReturns) {
                    $todaysReturns[$e->project->name][] = $e->amount . ' ' . $e->couponType->name;
                });

        return view('bank.deposit.index', [
            'projects' => $projects,
            'couponTypes' => $couponTypes,
            'selectedCouponType' => count($couponTypes) > 0 ? array_keys($couponTypes)[0] : null,
            'todaysReturns' => $todaysReturns,
        ]);
    }

    /**
     * Store a deposit.
     * 
     * @param  \App\Http\Requests\People\Bank\StoreDeposit  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDeposit $request) {
        $project = Project::findOrFail($request->project);
        $couponType = CouponType::findOrFail($request->coupon_type);
        $date = new Carbon($request->date);

        $coupon = CouponReturn::firstOrNew([
            'project_id' => $project->id,
            'coupon_type_id' => $couponType->id,
            'date' => $date
        ]);
        $coupon->amount = $coupon->amount + $request->amount;
		$coupon->date = $date;
        $coupon->project()->associate($project);
        $coupon->couponType()->associate($couponType);
        $coupon->save();

        return redirect()->route('bank.deposit')
            ->with('info', __('people.deposited_n_coupons_from_project', [ 
                    'amount' => $request->amount, 
                    'coupon' => $couponType->name, 
                    'project' => $project->name 
                ]));
    }

    /**
     * Show past deposits.
     * 
     * @return \Illuminate\Http\Response
     */
    public function transactions() {
        $transactions = CouponReturn
            ::orderBy('created_at', 'DESC')
            ->with(['user', 'project', 'couponType'])
            ->paginate(250);
        
        return view('bank.deposit.transactions', [
            'transactions' => $transactions,
        ]);
    }
}
