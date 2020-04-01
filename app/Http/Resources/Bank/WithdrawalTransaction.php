<?php

namespace App\Http\Resources\Bank;

use App\Models\Bank\CouponType;
use App\Models\People\Person;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalTransaction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->toDateTimeString(),
            'created_at_diff' => $this->created_at->diffForHumans(),
            'user' => optional($this->user)->name,
            'date' => optional($this->getDate())->toDateString(),
            'person' => $this->getPerson($request),
            'coupon' => $this->getCoupon(),
        ];
    }

    private function getDate() {
        $date = null;
        if (isset($this->getModified()['date'])) {
            $mdate = $this->getModified()['date'];
            if (isset($mdate['new'])) {
                $date = new Carbon($mdate['new']);
            } elseif (isset($mdate['old'])) {
                $date = new Carbon($mdate['old']);
            }
        }
        return $date;
    }

    private function getPerson($request)
    {
        $person = null;
        if (isset($this->getModified()['person_id']['new'])) {
            $person = Person::find($this->getModified()['person_id']['new']);
        } elseif (isset($this->getModified()['person_id']['old'])) {
            $person = Person::find($this->getModified()['person_id']['old']);
        }
        if ($person != null) {
            return [
                'url' => $request->user()->can('view', $person) ? route('bank.people.show', $person) : null,
                'name' => $person->full_name,
                'gender' => $person->gender,
                'date_of_birth' => $person->date_of_birth,
                'age' => $person->age != null ? __('people.age_n', [ 'age' => $person->age]) : null,
                'nationality' => $person->nationality,
            ];
        }
        return null;
    }

    private function getCoupon()
    {
        $coupon = null;
        if (isset($this->getModified()['coupon_type_id']['new'])) {
            $coupon = CouponType::find($this->getModified()['coupon_type_id']['new']);
        } elseif (isset($this->getModified()['coupon_type_id']['old'])) {
            $coupon = CouponType::find($this->getModified()['coupon_type_id']['old']);
        }
        if ($coupon != null) {
            $amount_diff = 0;
            if (isset($this->getModified()['amount']['new'])) {
                $amount_diff += $this->getModified()['amount']['new'];
            }
            if (isset($this->getModified()['amount']['old'])) {
                $amount_diff -= $this->getModified()['amount']['old'];
            }
            return [
                'name' => $coupon->name,
                'amount_diff' => $amount_diff,
            ];
        }
        return null;
    }
}
