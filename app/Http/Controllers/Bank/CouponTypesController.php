<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\StoreCouponType;
use App\Models\Bank\CouponType;

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
        return view('bank.coupons.index', [
            'coupons' => CouponType::orderBy('order')
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
        return view('bank.coupons.create', [
            'default_order' => optional(CouponType::selectRaw('MAX(`order`) as max_order')->first())->max_order + 1,
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
        $coupon->fill($request->all());
        $coupon->enabled = isset($request->enabled);
        $coupon->returnable = isset($request->returnable);
        $coupon->qr_code_enabled = isset($request->qr_code_enabled);
        $coupon->save();

        return redirect()
            ->route('coupons.index')
            ->with('success', __('coupons.coupon_added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(CouponType $coupon)
    {
        return view('bank.coupons.show', [
            'coupon' => $coupon,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(CouponType $coupon)
    {
        return view('bank.coupons.edit', [
            'coupon' => $coupon,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\People\Bank\StoreCouponType  $request
     * @param  \App\Models\Bank\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCouponType $request, CouponType $coupon)
    {
        $coupon->fill($request->all());
        $coupon->enabled = isset($request->enabled);
        $coupon->returnable = isset($request->returnable);
        $coupon->qr_code_enabled = isset($request->qr_code_enabled);
        $coupon->save();

        return redirect()
            ->route('coupons.show', $coupon)
            ->with('info', __('coupons.coupon_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(CouponType $coupon)
    {
        $coupon->delete();
        return redirect()
            ->route('coupons.index')
            ->with('success', __('coupons.coupon_deleted'));
    }
}
