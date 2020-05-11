<?php

namespace App\Http\Resources\Bank;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class BankPerson extends JsonResource
{
    protected $couponTypes = [];

    public function withCouponTypes($couponTypes) {
        $this->couponTypes = $couponTypes;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $community_volunteer = $this->linkedCommunityVolunteer();
        return [
            'id' => $this->{$this->getRouteKeyName()},
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'age' => $this->age,
            'nationality' => $this->nationality,
            'frequent_visitor' => $this->frequentVisitor,
            'can_view' => $request->user()->can('view', $this->resource),
            'can_update' => $request->user()->can('update', $this->resource),
            'show_url' => route('bank.people.show', $this),
            'edit_url' => route('bank.people.edit', $this),
            'card_no' => $this->card_no,
            'is_community_volunteer' => $community_volunteer !== null,
            'can_view_community_volunteer' => $community_volunteer !== null && $request->user()->can('view', $community_volunteer),
            'show_community_volunteer_url' => $community_volunteer !== null ? route('cmtyvol.show', $community_volunteer) : null,
            'police_no' => $this->police_no,
            'police_no_formatted' => $this->police_no_formatted,
            'remarks' => $this->remarks,
            'has_overdue_book_lendings' => $this->hasOverdueBookLendings,
            'can_operate_library' => Gate::allows('operate-library'),
            'library_lending_person_url' => route('library.lending.person', $this),
            'coupon_types' => collect($this->couponTypes)
                ->filter(fn ($coupon) => $this->eligibleForCoupon($coupon))
                ->map(fn ($coupon) => $this->mapCoupon($coupon))
                ->values(),
        ];
    }

    private function mapCoupon($coupon)
    {
        return [
            'id' => $coupon->id,
            'daily_amount' => $coupon->daily_amount,
            'icon' => $coupon->icon,
            'name' => $coupon->name,
            'min_age' => $coupon->min_age,
            'max_age' => $coupon->max_age,
            'qr_code_enabled' => $coupon->qr_code_enabled,
            'last_handout' => $this->canHandoutCoupon($coupon),
            'returning_possible' => optional($this->couponHandouts()
                ->where('coupon_type_id', $coupon->id)
                ->orderBy('date', 'desc')
                ->first()
            )->isReturningPossible,
        ];
    }
}
