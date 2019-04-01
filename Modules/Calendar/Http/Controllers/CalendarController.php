<?php

namespace Modules\Calendar\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index() {
        return view('calendar::index');
    }

}
