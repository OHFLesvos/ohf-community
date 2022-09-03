<?php

namespace App\Http\Resources\Visitors;

use Illuminate\Http\Resources\Json\JsonResource;

class Visitor extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'id_number' => $this->id_number,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'date_of_birth' => optional($this->date_of_birth)->toDateString(),
            'living_situation' => $this->living_situation,
            'remarks' => $this->remarks,
            'anonymized' => $this->anonymized,
            'liability_form_signed' => $this->liability_form_signed,
            'parental_consent_given' => $this->parental_consent_given,
            'checked_in_today' => $this->whenLoaded('checkins', fn () => $this->checkins->contains(fn ($checkin) => $checkin->created_at->isToday())),
        ];
    }
}
