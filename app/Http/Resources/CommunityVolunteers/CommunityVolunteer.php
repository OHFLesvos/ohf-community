<?php

namespace App\Http\Resources\CommunityVolunteers;

use App\Models\CommunityVolunteers\Responsibility;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin \App\Models\CommunityVolunteers\CommunityVolunteer
 */
class CommunityVolunteer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'family_name' => $this->family_name,
            'nickname' => $this->nickname,
            'full_name' => $this->full_name,
            'date_of_birth' => $this->date_of_birth,
            'age' => $this->age,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'languages' => $this->languages,
            'police_no' => $this->police_no,
            'portrait_picture' => $this->portrait_picture,
            'portrait_picture_url' => $this->portrait_picture != null
                ? Storage::url($this->portrait_picture)
                : null,
            'local_phone' => $this->local_phone,
            'other_phone' => $this->other_phone,
            'whatsapp' => $this->whatsapp,
            'whatsapp_link' => $this->whatsapp !== null ? whatsapp_link($this->whatsapp, 'Hello '.$this->first_name."\n") : null,
            'email' => $this->email,
            'skype' => $this->skype,
            'residence' => $this->residence,
            'pickup_location' => $this->pickup_location,
            'responsibilities' => $this->responsibilities->mapWithKeys(fn (Responsibility $r) => [
                $r->name => [
                    'id' => $r->id,
                    'description' => $r->description,
                    'start_date' => $r->getRelationValue('pivot')->start_date_string,
                    'end_date' => $r->getRelationValue('pivot')->end_date_string,
                ],
            ]),
            'first_work_start_date' => $this->first_work_start_date,
            'last_work_end_date' => $this->last_work_end_date,
            'working_since_days' => $this->working_since_days,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'url' => $request->user()->can('view', $this->resource)
                ? route('cmtyvol.show', $this)
                : null,
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
        ];
    }
}
