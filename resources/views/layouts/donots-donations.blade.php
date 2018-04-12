@extends('layouts.tabbed_view', [ 
    'nav_elements' => [
        [
            'url' => route('fundraising.donors.index'),
            'label' => __('fundraising.donors'),
            'icon' => 'handshake-o',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'fundraising.donors.index';
            },
            'authorized' => Auth::user()->can('list', \App\Donor::class)
        ],
        [
            'url' => route('fundraising.donations.index'),
            'label' => __('fundraising.donations'),
            'icon' => 'money',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'fundraising.donations.index';
            },
            'authorized' => Auth::user()->can('list', \App\Donation::class)
        ]
    ] 
])