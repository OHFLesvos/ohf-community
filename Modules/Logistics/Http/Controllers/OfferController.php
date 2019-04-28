<?php

namespace Modules\Logistics\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Logistics\Entities\Offer;
use Modules\Logistics\Http\Requests\CreateOfferRequest;
use Modules\Logistics\Http\Requests\UpdateOfferRequest;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OfferController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * 
     * @param CreateOfferRequest $request
     * @return Response
     */
    public function store(CreateOfferRequest $request)
    {
        $this->authorize('create', Offer::class);

        $offer = new Offer();
        $offer->fill($request->all());
        $offer->save();

        return redirect()
            ->route('logistics.suppliers.show', $offer->supplier)
            ->with('success', __('logistics::offers.offer_added'));
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param Offer $offer
     * @return Response
     */
    public function edit(Offer $offer)
    {
        $this->authorize('update', $offer);

        return view('logistics::offers.edit', [
            'offer' => $offer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Request $request
     * @param Offer $offer
     * @return Response
     */
    public function update(Request $request, Offer $offer)
    {
        $this->authorize('update', $offer);

        $offer->fill($request->all());
        $offer->save();

        return redirect()
            ->route('logistics.suppliers.show', $offer->supplier)
            ->with('success', __('logistics::offers.offer_updated'));
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Offer $offer
     * @return Response
     */
    public function destroy(Offer $offer)
    {
        $this->authorize('delete', $offer);

        $supplier = $offer->supplier;
        $offer->delete();

        return redirect()
            ->route('logistics.suppliers.show', $supplier)
            ->with('success', __('logistics::offers.offer_deleted'));
    }
}
