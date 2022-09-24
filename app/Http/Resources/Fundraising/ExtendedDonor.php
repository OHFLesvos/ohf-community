<?php

namespace App\Http\Resources\Fundraising;

use App\Models\Accounting\Budget;
use App\Models\Fundraising\Donation;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Fundraising\Donor
 */
class ExtendedDonor extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $can_view_donations = $request->user()->can('viewAny', Donation::class);
        $can_view_budgets = $request->user()->can('viewAny', Budget::class);

        return [
            'id' => $this->id,
            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company,
            'full_name' => $this->full_name,
            'street' => $this->street,
            'zip' => $this->zip,
            'city' => $this->city,
            'country_name' => $this->country_name,
            'full_address' => $this->full_address,
            'email' => $this->email,
            'phone' => $this->phone,
            'language' => $this->language,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
            'can_create_tag' => $request->user()->can('create', Tag::class),
            'can_view_donations' => $can_view_donations,
            'donations_count' => $can_view_donations ? $this->donations()->count() : null,
            'can_view_budgets' => $can_view_budgets,
            'budgets_count' => $can_view_budgets ? $this->budgets()->count() : null,
            'comments_count' => $this->comments()->count(),
        ];
    }
}
