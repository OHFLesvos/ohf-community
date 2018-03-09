<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jackiedo\LogReader\LogReader;

class LogViewerController extends Controller
{
    protected $reader;

    public function __construct(LogReader $reader)
    {
        $this->reader = $reader;
    }

    public function index() {
        $entries = $this->reader->orderBy('date', 'desc')->paginate(25);
        return view('logviewer.index', [
            'entries' => $entries,
        ]);
    }
}
