@extends('layouts.app_bottom_nav', [ 
    'bottom_nav_elements' => [
        [
            'url' => route('users.index'),
            'label' => __('app.manage_users'),
            'icon' => 'users',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'users.index';
            },
            'authorized' => Auth::user()->can('list', \App\User::class)
        ],
        [
            'url' => route('roles.index'),
            'label' => __('app.manage_roles'),
            'icon' => 'tags',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'roles.index';
            },
            'authorized' => Auth::user()->can('list', \App\Role::class)
        ]
    ] 
])