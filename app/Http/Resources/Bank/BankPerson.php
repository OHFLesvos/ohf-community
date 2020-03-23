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
        return [
            'id' => $this->{$this->getRouteKeyName()},
            'url' => route('api.bank.withdrawal.person', $this->resource),
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
            'gender_update_url' => route('api.people.setGender', $this),
            'date_of_birth_update_url' => route('api.people.setDateOfBirth', $this),
            'nationality_update_url' => route('api.people.setNationality', $this),
            'police_no_update_url' => route('api.people.updatePoliceNo', $this),
            'remarks_update_url' => route('api.people.updateRemarks', $this),
            'card_no' => $this->card_no,
            'register_card_url' => route('api.people.registerCard', $this),
            'is_helper' => optional($this->helper)->isActive,
            'can_view_helper' => $this->helper != null && $request->user()->can('view', $this->helper),
            'show_helper_url' => $this->helper != null ? route('people.helpers.show', $this->helper) : null,
            'police_no' => $this->police_no,
            'police_no_formatted' => $this->police_no_formatted,
            'remarks' => $this->remarks,
            'has_overdue_book_lendings' => $this->hasOverdueBookLendings,
            'can_operate_library' => Gate::allows('operate-library'),
            'library_lending_person_url' => route('library.lending.person', $this),
            'coupon_types' => collect($this->couponTypes)
                ->filter(function($coupon) {
                    return $this->eligibleForCoupon($coupon);
                })
                ->map(function($coupon) {
                    $returning_possible = optional($this->couponHandouts()
                        ->where('coupon_type_id', $coupon->id)
                        ->orderBy('date', 'desc')
                        ->first())->isReturningPossible;
                    return [
                        'id' => $coupon->id,
                        'daily_amount' => $coupon->daily_amount,
                        'icon' => $coupon->icon,
                        'name' => $coupon->name,
                        'min_age' => $coupon->min_age,
                        'max_age' => $coupon->max_age,
                        'qr_code_enabled' => $coupon->qr_code_enabled,
                        'last_handout' => $this->canHandoutCoupon($coupon),
                        'handout_url' => route('api.bank.withdrawal.handoutCoupon', [$this, $coupon]),
                        'returning_possible' => $returning_possible,
                    ];
                })
                ->values(),
        ];
    }
}
