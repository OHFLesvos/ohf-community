<?php

namespace Modules\KB\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'upload' => [
                'image'
            ],
        ]);

        $path = $request->file('upload')->store('public/kb/images');
        return response()->json([
            'url' => Storage::url($path),
        ], 201);
    }

}
