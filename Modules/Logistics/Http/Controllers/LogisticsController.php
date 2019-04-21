<?php

namespace Modules\Logistics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class LogisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return Response
     */
    public function index(Request $request)
    {
        return view('logistics::index');
    }

}
