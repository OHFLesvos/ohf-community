<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tag as TagResource;
use App\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Tag::class);

        $filter = $request->input('filter', '');

        return TagResource::collection(Tag::has('donors')
            ->forFilter($filter)
            ->orderBy('name')
            ->get());
    }
}
