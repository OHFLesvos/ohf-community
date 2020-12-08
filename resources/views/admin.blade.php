@extends('layouts.app')

@section('title', __('app.administration'))

@section('content')
    @php
        $currentRouteName = Route::currentRouteName();
        $nav_elements = [
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
        ];
    @endphp
    @foreach($nav_elements as $element)
        @if($element['authorized'])
            <p>
                <a href="{{ $element['url'] }}" class="btn btn-primary">
                    <x-icon :icon="$element['icon']"/>
                    {{ $element['label'] }}
                </a>
            </p>
        @endif
    @endforeach
@endsection
