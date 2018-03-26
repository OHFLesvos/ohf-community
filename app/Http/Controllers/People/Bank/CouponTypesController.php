<?php

namespace App\Http\Controllers\People\Bank;

use App\CouponType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\Bank\StoreCouponType;

class CouponTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', CouponType::class);

        return view('bank.coupons.index', [
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
        $this->authorize('create', CouponType::class);

        return view('bank.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\People\Bank\StoreCouponType  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCouponType $request)
    {
        $this->authorize('create', CouponType::class);

        $coupon = new CouponType();
        $coupon->name = $request->name;
        $coupon->icon = $request->icon;
        $coupon->daily_amount = $request->daily_amount;
        $coupon->retention_period = $request->retention_period;
        $coupon->min_age = $request->min_age;
        $coupon->max_age = $request->max_age;
        $coupon->order = $request->order;
        $coupon->enabled = isset($request->enabled);
        $coupon->returnable = isset($request->returnable);
        $coupon->save();

        return redirect()->route('coupons.index')
            ->with('success', __('people.coupon_added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(CouponType $coupon)
    {
        $this->authorize('view', $coupon);

        return view('bank.coupons.show', [
            'coupon' => $coupon,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(CouponType $coupon)
    {
        $this->authorize('update', $coupon);

        return view('bank.coupons.edit', [
            'coupon' => $coupon,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\People\Bank\StoreCouponType  $request
     * @param  \App\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCouponType $request, CouponType $coupon)
    {
        $this->authorize('update', $coupon);

        $coupon->name = $request->name;
        $coupon->icon = $request->icon;
        $coupon->daily_amount = $request->daily_amount;
        $coupon->retention_period = $request->retention_period;
        $coupon->min_age = $request->min_age;
        $coupon->max_age = $request->max_age;
        $coupon->order = $request->order;
        $coupon->enabled = isset($request->enabled);
        $coupon->returnable = isset($request->returnable);
        $coupon->save();

        return redirect()->route('coupons.show', $coupon)
            ->with('info', __('people.coupon_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CouponType  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(CouponType $coupon)
    {
        $this->authorize('delete', $coupon);

        $coupon->delete();
        return redirect()->route('coupons.index')
            ->with('success', __('people.coupon_deleted'));
    }
}
