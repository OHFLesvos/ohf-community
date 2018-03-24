<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Person;
use App\CouponHandout;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    const MONTHS_NO_TRANSACTIONS_SINCE = 2;

    /**
     * View for preparing person records cleanup.
     * 
     * @return \Illuminate\Http\Response
     */
    function maintenance() {
        return view('bank.maintenance', [
            'months_no_transactions_since' => self::MONTHS_NO_TRANSACTIONS_SINCE,
            'persons_without_coupons_since' => $this->getPersonsWithoutCouponsSince(self::MONTHS_NO_TRANSACTIONS_SINCE),
            'persons_without_coupons_ever' => $this->getPersonsWithoutCouponsEver(),
            'persons_without_number' => $this->getPersonsWithoutNumber(),
            'num_people' => Person::count(),
        ]);
    }

    /**
     * Gets the number of persons withn coupons since given number of months.
     * 
     * @param int $months number of months
     * @return int
     */
    private function getPersonsWithoutCouponsSince($months): int
    {
        return CouponHandout::groupBy('person_id')
            ->having(DB::raw('max(coupon_handouts.date)'), '<=', Carbon::today()->subMonth($months))
            ->where('worker', false)
            ->join('persons', function ($join) {
                $join->on('persons.id', '=', 'coupon_handouts.person_id')
                    ->whereNull('deleted_at');
            })
            ->get()
            ->count();
    }

    /**
     * Gets the number of persons which never received any coupons.
     * 
     * @return int
     */
    private function getPersonsWithoutCouponsEver(): int
    {
        return Person::leftJoin('coupon_handouts', function ($join) {
            $join->on('persons.id', '=', 'coupon_handouts.person_id')
                ->whereNull('deleted_at');
            })
            ->whereNull('coupon_handouts.id')
            ->where('worker', false)
            ->get()
            ->count();
    }

    /**
     * Gets the number of persons which do not have any number registered.
     * 
     * @return int
     */
    private function getPersonsWithoutNumber(): int
    {
        return Person::whereNull('police_no')
            ->whereNull('case_no')
            ->whereNull('medical_no')
            ->whereNull('registration_no')
            ->whereNull('section_card_no')
            ->whereNull('temp_no')
            ->where('worker', false)
            ->count();
    }

    /**
     * Cleanup person records.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    function updateMaintenance(Request $request) {
	    $cnt = 0;
        if (isset($request->cleanup_no_coupons_since)) {
            $ids = CouponHandout::groupBy('person_id')
                ->having(DB::raw('max(coupon_handouts.date)'), '<=', Carbon::today()->subMonth(self::MONTHS_NO_TRANSACTIONS_SINCE))
                ->where('worker', false)
                ->join('persons', function ($join) {
                    $join->on('persons.id', '=', 'coupon_handouts.person_id')
                        ->whereNull('deleted_at');
                })
                ->get()
                ->pluck('person_id')
                ->toArray();
                print_r($ids);
            $cnt += Person::destroy($ids);
        }
        if (isset($request->cleanup_no_coupons_ever)) {
            $ids = Person::leftJoin('coupon_handouts', function($join){
                $join->on('persons.id', '=', 'coupon_handouts.person_id')
                    ->whereNull('deleted_at');
                })
                ->whereNull('coupon_handouts.id')
                ->where('worker', false)
                ->select('persons.id')
                ->get()
                ->pluck('id')
                ->toArray();       
            $cnt += Person::destroy($ids);
        }
        if (isset($request->cleanup_no_number)) {
            $cnt +=  Person::whereNull('police_no')
                ->whereNull('case_no')
                ->whereNull('medical_no')
                ->whereNull('registration_no')
                ->whereNull('section_card_no')
                ->whereNull('temp_no')
                ->where('worker', false)
                ->delete();
        }
         return redirect()->route('bank.withdrawal')
             ->with('info', __('people.removed_n_persons', [ 'num' => $cnt ]));
    }

}
