<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CalendarResource;

class CalendarController extends Controller
{
    public function index() {
        return view('calendar.index');
    }

}
