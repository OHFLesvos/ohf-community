<?php

namespace App\Http\Controllers\Settings\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Setting;

class SettingsController extends Controller
{
    public function list(): JsonResponse
    {
        return response()
            ->json([
                'accounting.transactions.currency' => Setting::get('accounting.transactions.currency'),
                'accounting.transactions.use_locations' => (bool) Setting::get('accounting.transactions.use_locations', false),
                'accounting.transactions.use_secondary_categories' => (bool) Setting::get('accounting.transactions.use_secondary_categories', false),
                'accounting.transactions.use_cost_centers' => (bool) Setting::get('accounting.transactions.use_cost_centers', false),
                'accounting.transactions.show_intermediate_balances' => (bool) Setting::get('accounting.transactions.show_intermediate_balances', false),
                'visitors.nationalities' => Setting::get('visitors.nationalities', []),
                'visitors.living_situations' => Setting::get('visitors.living_situations', []),
                'visitors.purposes_of_visit' => Setting::get('visitors.purposes_of_visit', []),
                'visitors.retention_days' => config('visitors.retention_days'),
            ]);
    }
}
