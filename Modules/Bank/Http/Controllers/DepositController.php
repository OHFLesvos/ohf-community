<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Bank\Entities\Project;
use Modules\Bank\Entities\CouponReturn;
use Modules\Bank\Entities\CouponType;
use Modules\Bank\Http\Requests\StoreDeposit;

use Carbon\Carbon;

use OwenIt\Auditing\Models\Audit;

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

        $audits = CouponReturn
            ::whereDate('updated_at', Carbon::today())
            ->get()
            ->filter(function($e){
                return $e->audits->count() > 0;
            })
            ->flatMap(function($e){
                return $e->audits
                    ->filter(function($audit) {
                        return $audit->created_at->isToday();
                    })
                    ->map(function($audit) use($e) {
                        $amount_diff = $audit->getModified()['amount']['new'] ;
                        if (isset($audit->getModified()['amount']['old'])) {
                            $amount_diff -= $audit->getModified()['amount']['old'];
                        }
                        return [
                            'user' => $audit->user != null ? $audit->user->name : null,
                            'created_at' => $audit->created_at,
                            'project' => $e->project->name,
                            'coupon' => $e->couponType->name,
                            'amount_diff' => $amount_diff,
                            'date' => $e->date,
                        ];
                    });
            })
            ->sortByDesc('created_at');

        return view('bank::deposit.index', [
            'projects' => $projects,
            'couponTypes' => $couponTypes,
            'selectedCouponType' => count($couponTypes) > 0 ? array_keys($couponTypes)[0] : null,
            'audits' => $audits,
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
        return view('bank::deposit.transactions', [
            'transactions' => Audit
                ::where('auditable_type', 'Modules\Bank\Entities\CouponReturn')
                ->orderBy('created_at', 'DESC')
                ->paginate(50),
        ]);
    }
}
