<?php

namespace App\Http\Resources\Accounting;

use App\Support\Accounting\FormatsCurrency;
use App\Support\Accounting\Webling\Entities\Entrygroup;
use App\Support\Accounting\Webling\Exceptions\ConnectionException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Setting;

/**
 * @mixin \App\Models\Accounting\Transaction
 */
class Transaction extends JsonResource
{
    use FormatsCurrency;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            ...parent::toArray($request),
            'amount_formatted' => $this->formatCurrency($this->amount),
            'fees_formatted' => $this->formatCurrency($this->fees),
            'wallet_name' => $this->wallet->name,
            'category_full_name' => $this->category->getPathElements()->pluck('name')->join(' » '),
            'project_full_name' => $this->when($this->project !== null,
                fn () => $this->project->getPathElements()->pluck('name')->join(' » ')),
            'supplier' => $this->whenLoaded('supplier',
                fn () => new Supplier($this->supplier)),
            'budget_name' => $this->when($this->budget_id !== null,
                fn () => optional($this->budget)->name),
            'can_update' => $request->user()->can('update', $this->resource),
            'can_update_receipt' => $request->user()->can('updateReceipt', $this->resource),
            'can_update_metadata' => $request->user()->can('updateMetadata', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
            'controller_name' => $this->when($this->controlled_by !== null,
                fn () => optional($this->controller)->name),
            'can_control' => $request->user()->can('control', $this->resource),
            'can_undo_controlling' => $request->user()->can('undoControlling', $this->resource),
            'can_book_externally' => $request->user()->can('book-accounting-transactions-externally'),
            'can_undo_booking' => $request->user()->can('undoBooking', $this->resource),
            'external_url' => $this->when($this->external_id !== null,
                fn () => $this->getExternalUrl()),
            'receipt_pictures' => $this->receiptPictureArray(),
            'intermediate_balance' => Setting::get('accounting.transactions.show_intermediate_balances')
                ? $this->getIntermediateBalance()
                : null,
        ];
    }

    private function getExternalUrl(): ?string
    {
        try {
            return optional(Entrygroup::find($this->external_id))->url();
        } catch (ConnectionException $e) {
            Log::warning('Unable to get external URL: '.$e->getMessage());
        }

        return null;
    }
}
