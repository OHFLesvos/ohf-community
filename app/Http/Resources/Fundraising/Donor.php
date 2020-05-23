<?php

namespace App\Http\Resources\Fundraising;

use App\Models\Comment;
use App\Models\Fundraising\Donor as DonorModel;
use App\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

class Donor extends JsonResource
{
    private ?bool $extended;

    public function __construct($resource, ?bool $extended = false)
    {
        parent::__construct($resource);

        $this->extended = $extended;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->extended) {
            $can_view_donations = $request->user()->can('viewAny', DonorModel::class);
            return [
                'id' => $this->id,
                'salutation' => $this->salutation,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'company' => $this->company,
                'fullAddress' => $this->fullAddress,
                'email' => $this->email,
                'phone' => $this->phone,
                'language' => $this->language,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'can_create_tag' => $request->user()->can('create', Tag::class),
                'can_create_comment' => $request->user()->can('create', Comment::class),
                'can_create_donation' => $request->user()->can('create', DonorModel::class),
                'can_view_donations' => $can_view_donations,
                'donations_count' => $can_view_donations ? $this->donations()->count() : null,
                'comments_count' => $this->comments()->count(),
            ];
        }

        return [
            'id' => $this->id,
            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company,
            'street' => $this->street,
            'zip' => $this->zip,
            'city' => $this->city,
            'country_name' => $this->country_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'language' => $this->language,
            'can_update' => $request->user()->can('update', $this->resource),
            'can_delete' => $request->user()->can('delete', $this->resource),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
