<?php

namespace App\Http\Resources\CommunityVolunteers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommunityVolunteer extends JsonResource
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
            'name' => $this->person->name,
            'family_name' => $this->person->family_name,
            'nickname' => $this->person->nickname,
            'full_name' => $this->person->fullName,
            'date_of_birth' => $this->person->date_of_birth,
            'age' => $this->person->age,
            'gender' => $this->person->gender,
            'remarks' => $this->person->remarks,
            'nationality' => $this->person->nationality,
            'languages' => $this->person->languages,
            'police_no' => $this->person->police_no,
            'portrait_picture' => $this->person->portrait_picture,
            'portrait_picture_url' => $this->person->portrait_picture != null
                ? Storage::url($this->person->portrait_picture)
                : null,
            'local_phone' => $this->local_phone,
            'other_phone' => $this->other_phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'skype' => $this->skype,
            'residence' => $this->residence,
            'pickup_location' => $this->pickup_location,
            'work_starting_date' => $this->work_starting_date,
            'work_leaving_date' => $this->work_leaving_date,
            'responsibilities' => $this->responsibilities->pluck('name'),
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'url' => request()->user()->can('view', $this->resource) ? route('cmtyvol.show', $this) : null,
        ];
    }
}
