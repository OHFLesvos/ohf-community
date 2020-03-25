@extends('layouts.tabbed_view', [
    'nav_elements' => [
        [
            'url' => route('accounting.transactions.index'),
            'label' => __('accounting.transactions'),
            'icon' => 'list',
            'active' => fn ($currentRouteName) => $currentRouteName == 'accounting.transactions.index',
            'authorized' => Auth::user()->can('list', App\Models\Accounting\MoneyTransaction::class)
        ],
        [
            'url' => route('accounting.transactions.summary'),
            'label' => __('accounting.summary'),
            'icon' => 'calculator',
            'active' => fn ($currentRouteName) => $currentRouteName == 'accounting.transactions.summary',
            'authorized' => Gate::allows('view-accounting-summary')
        ]
    ]
])