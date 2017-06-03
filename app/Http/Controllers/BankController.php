<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankController extends Controller
{
    function index() {
		return view('bank', [
		]);
    }
}
