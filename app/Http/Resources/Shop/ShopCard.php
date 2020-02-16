<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\Resource;

class ShopCard extends Resource
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
            'date' => $this->date,
            'code_short' => substr($this->code, 0, 7),
            'code_redeemed' => $this->code_redeemed,
            'expired' => $this->isCodeExpired(),
            'updated_diff_formatted' => $this->updated_at->diffForHumans(),
            'validity_formatted' => $this->couponType->code_expiry_days != null ? trans_choice('app.valid_for_n_days', $this->couponType->code_expiry_days, ['days' => $this->couponType->code_expiry_days]) : null,
            'redeem_url' => route('shop.cards.redeem', $this),
            'cancel_url' => route('shop.cards.cancel', $this),
            'person' => $this->getPersonData(),
        ];
    }

    private function getPersonData()
    {
        if ($this->person != null) {
            return [
                'fullName' => $this->person->fullName,
                'gender' => $this->person->gender,
                'date_of_birth' => $this->person->date_of_birth,
                'age_formatted' => __('people.age_n', ['age' => $this->person->age]),
                'nationality' => $this->person->nationality,
                'url' => route('bank.people.show', $this->person),
                'family_members' => $this->getFamilyMembers(),
            ];
        }
        return null;
    }

    private function getFamilyMembers()
    {
        return $this->person->familyMembers
            ->map(function($person){
                return [
                    'fullName' => $person->fullName,
                    'age_formatted' => __('people.age_n', ['age' => $person->age]),
                ];
            })
            ->toArray();
    }
}
