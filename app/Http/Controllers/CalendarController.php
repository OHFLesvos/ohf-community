<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CalendarEventType;

class CalendarController extends Controller
{
    public function index() {
        return view('calendar.index', [
            'types' => CalendarEventType::orderBy('name')->get(),
        ]);
    }

}
