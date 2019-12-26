<?php

namespace Modules\Shop\Transformers;

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
        $data = [];

        $data['date'] = $this->date;
        $data['code_short'] = substr($this->code, 0, 7);
        $data['code_redeemed'] = $this->code_redeemed;
        $data['expired'] = $this->isCodeExpired();
        $data['updated_diff_formatted'] = $this->updated_at->diffForHumans();
        $data['validity_formatted'] = $this->couponType->code_expiry_days != null ? trans_choice('app.valid_for_n_days', $this->couponType->code_expiry_days, ['days' => $this->couponType->code_expiry_days]) : null;
        $data['redeem_url'] = route('shop.redeemCard', $this);
        $data['cancel_url'] = route('shop.cancelCard', $this);
        $data['person'] = $this->getPersonData();

        return $data;
    }

    private function getPersonData()
    {
        if ($this->person != null) {
            $data = [];
            $data['fullName'] = $this->person->fullName;
            $data['gender'] = $this->person->gender;
            $data['date_of_birth'] = $this->person->date_of_birth;
            $data['age_formatted'] = __('people::people.age_n', ['age' => $this->person->age]);
            $data['nationality'] = $this->person->nationality;
            $data['url'] = route('people.show', $this->person);

            $data['children'] = [];
            $children = $this->person->children;
            if (count($children) > 0) {
                foreach($children as $child) {
                    $data['children'][] = [
                        'fullName' => $child->fullName,
                        'age_formatted' => __('people::people.age_n', ['age' => $child->age]),
                    ];
                }
            }
            return $data;
        }
        return null;
    }
}
