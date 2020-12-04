<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTags;
use App\Http\Resources\Tag as TagResource;
use App\Models\Fundraising\Donor;
use App\Models\Tag;

class DonorTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Donor $donor)
    {
        $this->authorize('viewAny', Tag::class);

        return TagResource::collection($donor->tagsSorted);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Donor $donor, StoreTags $request)
    {
        $this->authorize('create', Tag::class);

        $donor->setTags(collect($request->input('tags', [])));

        return TagResource::collection($donor->tagsSorted)
            ->additional([
                'message' => __('app.tags_updated'),
            ]);
    }
}
