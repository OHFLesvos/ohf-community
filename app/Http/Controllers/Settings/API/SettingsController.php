<?php

namespace App\Http\Controllers\Settings\API;

use App\Http\Controllers\Controller;
use Setting;

class SettingsController extends Controller
{
    public function list()
    {
        return response()->json([
            'accounting.transactions.currency' => Setting::get('accounting.transactions.currency'),
            'accounting.transactions.use_locations' => (bool) Setting::get('accounting.transactions.use_locations') ?? false,
            'accounting.transactions.use_secondary_categories' => (bool) Setting::get('accounting.transactions.use_secondary_categories') ?? false,
            'accounting.transactions.use_cost_centers' => (bool) Setting::get('accounting.transactions.use_cost_centers') ?? false,
            'accounting.transactions.show_intermediate_balances' => Setting::get('accounting.transactions.show_intermediate_balances') ?? false,
            'visitors.nationalities' => [
                "Afghanistan",
                "Algeria",
                "Angola",
                "Bangladesh",
                "Burkina Faso",
                "Burundi",
                "Cameroon",
                "Comoros",
                "Djibouti",
                "DRC",
                "Egypt",
                "Eritrea",
                "Gambia",
                "Ghana",
                "Guinea",
                "Iran",
                "Iran - Kurd",
                "Iraq",
                "Ivory Coast",
                "Kuwait",
                "Mali",
                "Morocco",
                "Nepal",
                "Nigeria",
                "North Sudan",
                "Pakistan",
                "Palestine",
                "Senegal",
                "Sierra Leone",
                "Somalia",
                "Stateless",
                "Sudan",
                "Syria",
                "Tajikistan",
                "Togo",
                "Uganda",
                "Yemen",
            ],
            "visitors.living_situation" => [
                'RIC Lesvos',
                'Self accommodated',
                'NGO Accommodation',
                'other'
            ],
            'visitors.purposes_of_visit' => [
                'OHF',
                'MVI',
                'Makerspace',
                'Sporos',
                'Wave of Hope',
                'ReFocus',
                'Intersos',
                'ISOP',
                'YSR'
            ]
        ]);
    }
}
