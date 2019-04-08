<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;

use Modules\Bank\Entities\CouponHandout;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class MaintenanceController extends Controller
{
    const MONTHS_NO_TRANSACTIONS_SINCE = 2;

    /**
     * View for preparing person records cleanup.
     * 
     * @return \Illuminate\Http\Response
     */
    function maintenance() {
        return view('bank::maintenance', [
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
            // TODO: consider helper status
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
            ->doesntHave('helper')
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
            ->whereNull('case_no_hash')
            ->whereNull('registration_no')
            ->whereNull('section_card_no')
            ->doesntHave('helper')
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
                // TODO: consider helper status
                ->join('persons', function ($join) {
                    $join->on('persons.id', '=', 'coupon_handouts.person_id')
                        ->whereNull('deleted_at');
                })
                ->get()
                ->pluck('person_id')
                ->toArray();
            // Ignore helpers
            $ids_wo_helpers = Person::find($ids)->load('helper')->where('helper', null)->pluck('id')->toArray();
            $cnt += Person::destroy($ids_wo_helpers);
        }
        if (isset($request->cleanup_no_coupons_ever)) {
            $ids = Person::leftJoin('coupon_handouts', function($join){
                $join->on('persons.id', '=', 'coupon_handouts.person_id')
                    ->whereNull('deleted_at');
                })
                ->whereNull('coupon_handouts.id')
                ->doesntHave('helper')
                ->select('persons.id')
                ->get()
                ->pluck('id')
                ->toArray();       
            $cnt += Person::destroy($ids);
        }
        if (isset($request->cleanup_no_number)) {
            $cnt +=  Person::whereNull('police_no')
                ->whereNull('case_no_hash')
                ->whereNull('registration_no')
                ->whereNull('section_card_no')
                ->doesntHave('helper')
                ->delete();
        }
         return redirect()->route('bank.withdrawal')
             ->with('info', __('people::people.removed_n_persons', [ 'num' => $cnt ]));
    }

}
