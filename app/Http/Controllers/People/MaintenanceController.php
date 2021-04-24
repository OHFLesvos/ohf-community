<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\Bank\CouponHandout;
use App\Models\People\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;

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
        return view('people.maintenance', [
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
    public function updateMaintenance(Request $request)
    {
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
        return redirect()->route('people.index')
             ->with('info', __('people.removed_n_persons', [ 'num' => $cnt ]));
    }

    public function duplicates()
    {
        $names = [];
        Person::orderBy('family_name')
            ->orderBy('name')
            ->get()
            ->each(function ($e) use (&$names) {
                $names[$e->name . ' ' . $e->family_name][$e->id] = $e;
            });
        $duplicates = collect($names)
            ->filter(fn ($e) => count($e) > 1);

        return view('people.duplicates', [
            'duplicates' => $duplicates,
            'total' => Person::count(),
            'actions' => [
                'nothing' => 'Do nothing',
                'merge' => 'Merge',
            ],
        ]);
    }

    public function applyDuplicates(Request $request)
    {
        Validator::make($request->all(), [
            'action' => 'required|array',
        ])->validate();
        $merged = 0;
        foreach ($request->action as $idsString => $action) {
            if ($action == 'merge') {
                $ids = explode(',', $idsString);
                self::mergePersons($ids);
                $merged++;
            }
        }

        return redirect()->route('people.maintenance')
            ->with('success', 'Done (merged ' . $merged . ' persons).');
    }

    private static function mergePersons($ids)
    {
        // Get master and related persons
        $persons = Person::whereIn('public_id', $ids)
            ->orderBy('created_at', 'desc')
            ->get();
        $master = $persons->shift();

        // Merge basic attributes
        $attributes = [
            'gender',
            'date_of_birth',
            'nationality',
            'languages',
            'police_no',
            'card_no',
            'card_issued',
        ];
        foreach ($attributes as $attr) {
            if ($master->$attr == null) {
                $master->$attr = self::getFirstNonEmptyAttributeFromCollection($persons, $attr);
            }
        }

        // Merge coupon handouts
        CouponHandout::whereIn('person_id', $persons->pluck('id')->toArray())
            ->get()
            ->each(function ($e) use ($master) {
                $e->person_id = $master->id;
                try {
                    $e->save();
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Ignore
                    Log::notice('Skip adapting coupon handout during merge: ' . $ex->getMessage());
                }
            });

        // Merge remarks
        $remarks = $persons->pluck('remarks')
            ->push($master->remarks)
            ->filter(fn ($e) => $e != null)
            ->unique()
            ->implode("\n");
        if (! empty($remarks)) {
            $master->remarks = $remarks;
        }

        // Save master, remove duplicates
        $master->save();
        $persons->each(function ($e) {
            $e->forceDelete();
        });

        return count($ids);
    }

    private static function getFirstNonEmptyAttributeFromCollection($collection, $attributeName)
    {
        return $collection->pluck($attributeName)
            ->filter(fn ($e) => $e != null)
            ->first();
    }
}
