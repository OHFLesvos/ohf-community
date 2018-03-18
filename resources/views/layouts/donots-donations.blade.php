@extends('layouts.app_bottom_nav', [ 
    'bottom_nav_elements' => [
        [
            'url' => route('donors.index'),
            'label' => __('donations.donors'),
            'icon' => 'handshake-o',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'donors.index';
            },
            'authorized' => Auth::user()->can('list', \App\Donor::class)
        ],
        [
            'url' => route('donations.index'),
            'label' => __('donations.donations'),
            'icon' => 'money',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'donations.index';
            },
            'authorized' => Auth::user()->can('list', \App\Donation::class)
        ]
    ] 
])