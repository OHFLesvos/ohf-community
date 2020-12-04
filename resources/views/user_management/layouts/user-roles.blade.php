@extends('layouts.tabbed_view', [
    'nav_elements' => [
        [
            'url' => route('users.index'),
            'label' => __('app.users'),
            'icon' => 'users',
            'active' => fn ($currentRouteName) => $currentRouteName == 'users.index',
            'authorized' => Auth::user()->can('viewAny', \App\Models\User::class)
        ],
        [
            'url' => route('roles.index'),
            'label' => __('app.roles'),
            'icon' => 'tags',
            'active' => fn ($currentRouteName) => $currentRouteName == 'roles.index',
            'authorized' => Auth::user()->can('viewAny', \App\Models\Role::class)
        ]
    ]
])
