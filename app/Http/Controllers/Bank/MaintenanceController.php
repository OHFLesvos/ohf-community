<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\CouponHandout;
use App\Models\People\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    private const MONTHS_NO_TRANSACTIONS_SINCE = 2;

    /**
     * View for preparing person records cleanup.
     *
     * @return \Illuminate\Http\Response
     */
    public function maintenance()
    {
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
        return Person::query()
            ->leftJoin('coupon_handouts', function ($join) {
                $join->on('persons.id', '=', 'coupon_handouts.person_id')
                    ->whereNull('deleted_at');
            })
            ->whereNull('coupon_handouts.id')
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
            ->count();
    }

    /**
     * Cleanup person records.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateMaintenance(Request $request) {
        $cnt = 0;
        if (isset($request->cleanup_no_coupons_since)) {
            $ids = CouponHandout::groupBy('person_id')
                ->having(DB::raw('max(coupon_handouts.date)'), '<=', Carbon::today()->subMonth(self::MONTHS_NO_TRANSACTIONS_SINCE))
                ->join('persons', function ($join) {
                    $join->on('persons.id', '=', 'coupon_handouts.person_id')
                        ->whereNull('deleted_at');
                })
                ->get()
                ->pluck('person_id')
                ->toArray();

            $ids_wo_cmtyvol = Person::find($ids)
                ->pluck('id')
                ->toArray();
            $cnt += Person::destroy($ids_wo_cmtyvol);
        }
        if (isset($request->cleanup_no_coupons_ever)) {
            $ids = Person::query()
                ->leftJoin('coupon_handouts', function ($join) {
                    $join->on('persons.id', '=', 'coupon_handouts.person_id')
                        ->whereNull('deleted_at');
                })
                ->whereNull('coupon_handouts.id')
                ->select('persons.id')
                ->get()
                ->pluck('id')
                ->toArray();
            $cnt += Person::destroy($ids);
        }
        if (isset($request->cleanup_no_number)) {
            $cnt += Person::whereNull('police_no')
                ->delete();
        }
         return redirect()->route('bank.withdrawal.search')
             ->with('info', __('people.removed_n_persons', [ 'num' => $cnt ]));
    }

}
