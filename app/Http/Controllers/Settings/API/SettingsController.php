<?php

namespace App\Http\Controllers\Settings\API;

use App\Http\Controllers\Controller;
use Setting;

class SettingsController extends Controller
{
    public function list()
    {
        return response()->json([
            'accounting.transactions.use_locations' => (bool) Setting::get('accounting.transactions.use_locations') ?? false,
            'accounting.transactions.use_secondary_categories' => (bool) Setting::get('accounting.transactions.use_secondary_categories') ?? false,
            'accounting.transactions.use_cost_centers' => (bool) Setting::get('accounting.transactions.use_cost_centers') ?? false,
            'accounting.transactions.show_intermediate_balances' => Setting::get('accounting.transactions.show_intermediate_balances') ?? false,
        ]);
    }
}
