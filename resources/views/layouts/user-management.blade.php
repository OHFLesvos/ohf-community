@extends('layouts.app', [
    'subnav' => [
        [
            'url' => route('users.index'),
            'caption' => __('Users'),
            'icon' => 'users',
            'active' => 'admin/users*',
            'authorized' => Auth::user()->can('viewAny', App\Models\User::class),
        ],
        [
            'url' => route('roles.index'),
            'caption' => __('Roles'),
            'icon' => 'tags',
            'active' => 'admin/roles*',
            'authorized' => Auth::user()->can('viewAny', App\Models\Role::class),
        ]
    ],
])
