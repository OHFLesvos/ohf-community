@extends('layouts.tabbed_view', [ 
    'nav_elements' => [
        [
            'url' => route('users.index'),
            'label' => __('app.users'),
            'icon' => 'users',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'users.index';
            },
            'authorized' => Auth::user()->can('list', \App\User::class)
        ],
        [
            'url' => route('roles.index'),
            'label' => __('app.roles'),
            'icon' => 'tags',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'roles.index';
            },
            'authorized' => Auth::user()->can('list', \App\Role::class)
        ]
    ] 
])