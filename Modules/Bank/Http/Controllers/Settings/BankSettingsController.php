<?php

namespace Modules\Bank\Http\Controllers\Settings;

use App\Http\Controllers\Settings\SettingsController;

use Modules\Bank\Http\Controllers\Reporting\BankReportingController;

use Modules\People\Entities\Person;

use Modules\KB\Entities\WikiArticle;

use Illuminate\Support\Facades\Config;

class BankSettingsController extends SettingsController
{
    protected function getSections() {
        return [
            'coupons' => __('bank::coupons.coupons'),
            'display_settings' => __('people::people.display_settings'),
            'frequent_visitors' => __('people::people.frequent_visitors'),
        ];
    }

    protected function getSettings() {
        return [
            'bank.undo_coupon_handout_grace_period' => [
                'default' => Config::get('bank.undo_coupon_handout_grace_period'),
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'required|numeric|min:1',
                'label_key' => 'bank::coupons.undo_coupon_handout_grace_period_seconds',
                'section' => 'coupons',
            ],
            'bank.frequent_visitor_weeks' => [
                'default' => Config::get('bank.frequent_visitor_weeks'),
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'required|numeric|min:1',
                'label_key' => 'people::people.number_of_weeks',
                'section' => 'frequent_visitors',
                'include_pre' => 'bank::settings.frequent_visitors_explanation',
            ],
            'bank.frequent_visitor_threshold' => [
                'default' => Config::get('bank.frequent_visitor_threshold'),
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'required|numeric|min:1',
                'label_key' => 'people::people.min_number_of_visits',
                'section' => 'frequent_visitors',
                'include_post' => [ 'bank::settings.frequent_visitors_affected', [
                    'current_num_people' => Person::count(),
                    'current_num_frequent_visitors' => BankReportingController::getNumberOfFrequentVisitors(),
                ] ],
            ],
            'bank.help_article' => is_module_enabled('KB') ? [
                'default' => null,
                'form_type' => 'select',
                'form_list' => WikiArticle::orderBy('title')->get()->pluck('title', 'id')->toArray(),
                'form_placeholder' => __('kb::wiki.select_article'),
                'form_validate' => 'nullable|exists:kb_articles,id',
                'label_key' => 'kb::wiki.help_article',
                'section' => 'display_settings',
            ] : null,
        ];
    }

    protected function getUpdateRouteName() {
        return 'bank.settings.update';
    }

    protected function getRedirectRouteName() {
        return 'bank.withdrawal';
    }

}
