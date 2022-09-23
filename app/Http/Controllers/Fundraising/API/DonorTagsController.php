<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTags;
use App\Http\Resources\Tag as TagResource;
use App\Models\Fundraising\Donor;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

class DonorTagsController extends Controller
{
    public function index(Donor $donor): JsonResource
    {
        $this->authorize('viewAny', Tag::class);

        return TagResource::collection($donor->tagsSorted);
    }

    public function store(Donor $donor, StoreTags $request): JsonResource
    {
        $this->authorize('create', Tag::class);

        $donor->setTags($request->input('tags', []));

        return TagResource::collection($donor->tagsSorted)
            ->additional([
                'message' => __('Tags updated.'),
            ]);
    }
}
