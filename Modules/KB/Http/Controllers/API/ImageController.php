<?php

namespace Modules\KB\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\KB\Entities\WikiArticle;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class ImageController extends Controller
{
    private const DIRECTORY = 'public/kb/images';

    public function index()
    {
        $this->authorize('list', WikiArticle::class);

        $files = collect(Storage::files(self::DIRECTORY))->map(function($f){
            return [
                'name' => basename(storage_path('app/' . $f)),
                'size' => Storage::size($f),
                'modified' => Carbon::createFromTimestamp(Storage::lastModified($f))->toDateTimeString(),
                'url' => Storage::url($f),
            ];
        });
        return response()->json($files);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', WikiArticle::class);

        $request->validate([
            'upload' => [
                'image',
            ],
        ]);

        $path = $request->file('upload')->store(self::DIRECTORY);
        return response()->json([
            'url' => Storage::url($path),
        ], 201);
    }

    public function destroy(string $image, Request $request)
    {
        $this->authorize('create', WikiArticle::class);

        $path = self::DIRECTORY . '/' . $image;
        if (Storage::exists($path)) {
            Storage::delete($path);
            return response(null, 204);
        }
        return response(null, 404); 
    }
}
