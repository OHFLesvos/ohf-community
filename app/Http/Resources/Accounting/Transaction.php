<?php

namespace App\Http\Resources\Accounting;

use App\Support\Accounting\FormatsCurrency;
use Illuminate\Http\Resources\Json\JsonResource;
use Setting;

class Transaction extends JsonResource
{
    use FormatsCurrency;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['amount_formatted'] = $this->formatCurrency($this->amount, $this->wallet->currency);
        $data['fees_formatted'] = $this->formatCurrency($this->fees, $this->wallet->currency);
        $data['wallet_name'] = $this->wallet->name;
        $data['category_full_name'] = $this->category->getPathElements()->pluck('name')->join(' » ');
        $data['project_full_name'] = $this->when($this->project !== null, fn () => $this->project->getPathElements()->pluck('name')->join(' » '));
        $data['supplier'] = $this->whenLoaded('supplier', fn () => new Supplier($this->supplier));
        $data['budget_name'] = $this->when($this->budget_id !== null, fn () => optional($this->budget)->name);
        $data['can_update'] = $request->user()->can('update', $this->resource);
        $data['can_delete'] = $request->user()->can('delete', $this->resource);
        $data['controller_name'] = $this->when($this->controlled_by !== null, fn () => optional($this->controller)->name);
        $data['can_control'] = $request->user()->can('control', $this->resource);
        $data['can_undo_controlling'] = $request->user()->can('undoControlling', $this->resource);
        $data['can_book_externally'] = $request->user()->can('book-accounting-transactions-externally');
        $data['can_undo_booking'] = $request->user()->can('undoBooking', $this->resource);
        $data['external_url'] = $this->when($this->external_id !== null, fn () => $this->externalUrl);
        $data['receipt_pictures'] = $this->receiptPictureArray();
        $data['intermediate_balance'] = Setting::get('accounting.transactions.show_intermediate_balances') ? $this->getIntermediateBalance() : null;

        return $data;
    }
}
