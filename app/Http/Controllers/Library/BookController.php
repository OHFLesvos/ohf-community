<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LibraryBook;

class BookController extends Controller
{
	public function filter(Request $request) {
        $qry = LibraryBook::limit(10)
            ->orderBy('title')
            ->orderBy('author');
        if (isset($request->query()['query'])) {
            $qry->where('title', 'LIKE', '%' . $request->query()['query'] . '%')
                ->orWhere('author', 'LIKE', '%' . $request->query()['query'] . '%')
                ->orWhere('isbn', 'LIKE', preg_replace('/[^+0-9x]/i', '', $request->query()['query']) . '%');
        }
        $records = $qry->get()
            ->map(function($e){ 
                $val = $e->title;
                if (!empty($e->author)) {
                    $val.= ' (' . $e->author . ')';
                }
                if (!empty($e->isbn)) {
                    $val.= ', ' . $e->isbn13;
                }
                return [
                    'value' => $val,
                    'data' => $e->id,
                ]; 
            });
        return response()->json(["suggestions" => $records]);
    }
}
