<?php

namespace App\Http\Controllers\Logviewer;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Jackiedo\LogReader\LogReader;

class LogViewerController extends Controller
{
    protected $reader;

    private static $levels = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug'
    ];

    public function __construct(LogReader $reader)
    {
        $this->reader = $reader;
    }

    public function index(Request $request) {
        $request->validate([
            'level' => 'array|in:' . implode(',', self::$levels),
        ]);

        $entries = $this->reader->orderBy('date', 'desc');
        if (!empty($request->level)) {
            $activeLevels = $request->level;
            $entries->level($activeLevels);
        } else {
            $activeLevels = self::$levels;
        }
        return view('logviewer.index', [
            'entries' => $entries->paginate(25, null, [
                'path' => route('logviewer.index'),
            ]),
            'levels' => self::$levels,
            'activeLevels' => $activeLevels,
        ]);
    }

}
