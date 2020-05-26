<?php

namespace App\Http\Controllers\Fundraising;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class FundraisingController extends Controller
{
    public function index()
    {
        $this->authorize('view-fundraising');

        return view('fundraising.index', [
            'permissions' => [
                'view-fundraising-entities' => Gate::allows('view-fundraising-entities'),
                'manage-fundraising-entities' => Gate::allows('manage-fundraising-entities'),
                'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
            ],
        ]);
    }
}
