<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('operate-library');

        return view('library.index', [
            'permissions' => [
                'operate-library' => Gate::allows('operate-library'),
                'configure-library' => Gate::allows('configure-library'),
                'view-books' => $request->user()->can('viewAny', LibraryBook::class),
                'create-books' => $request->user()->can('create', LibraryBook::class),
            ],
        ]);
    }
}
