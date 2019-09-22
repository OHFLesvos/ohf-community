<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Bank\Entities\CouponType;
use Modules\Bank\Http\Requests\StoreCouponType;

use Illuminate\Support\Facades\DB;

class CouponTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(CouponType::class, 'coupon');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bank::coupons.index', [
            'coupons' => CouponType
                ::orderBy('order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank::coupons.create', [
            'default_order' => optional(CouponType::select(DB::raw('MAX(`order`) as max_order'))->first())->max_order + 1,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\People\Bank\StoreCouponType  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCouponType $request)
    {
        $coupon = new CouponType();
        $coupon->name = $request->name;
        $coupon->icon = $request->icon;
        $coupon->daily_amount = $request->daily_amount;
        $coupon->retention_period = $request->retention_period;
        $coupon->min_age = $request->min_age;
        $coupon->max_age = $request->max_age;
        $coupon->daily_spending_limit = $request->daily_spending_limit;
        $coupon->newly_registered_block_days = $request->newly_registered_block_days;
        $coupon->order = $request->order;
        $coupon->enabled = isset($request->enabled);
        $coupon->returnable = isset($request->returnable);
        $coupon->qr_code_enabled = isset($request->qr_code_enabled);
        $coupon->allow_for_helpers = isset($request->allow_for_helpers);
        $coupon->save();

        return redirect()->route('coupons.index')
            ->with('success', __('bank::coupons.coupon_added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Modules\Bank\Entities\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(CouponType $coupon)
    {
        return view('bank::coupons.show', [
            'coupon' => $coupon,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Modules\Bank\Entities\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(CouponType $coupon)
    {
        return view('bank::coupons.edit', [
            'coupon' => $coupon,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\People\Bank\StoreCouponType  $request
     * @param  \Modules\Bank\Entities\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCouponType $request, CouponType $coupon)
    {
        $coupon->name = $request->name;
        $coupon->icon = $request->icon;
        $coupon->daily_amount = $request->daily_amount;
        $coupon->retention_period = $request->retention_period;
        $coupon->min_age = $request->min_age;
        $coupon->max_age = $request->max_age;
        $coupon->daily_spending_limit = $request->daily_spending_limit;
        $coupon->newly_registered_block_days = $request->newly_registered_block_days;
        $coupon->order = $request->order;
        $coupon->enabled = isset($request->enabled);
        $coupon->returnable = isset($request->returnable);
        $coupon->qr_code_enabled = isset($request->qr_code_enabled);
        $coupon->allow_for_helpers = isset($request->allow_for_helpers);
        $coupon->save();

        return redirect()->route('coupons.show', $coupon)
            ->with('info', __('bank::coupons.coupon_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Bank\Entities\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(CouponType $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')
            ->with('success', __('bank::coupons.coupon_deleted'));
    }
}
