@php
    $links = [
        [
            'url' => route('users.index'),
            'title' => __('app.manage'),
            'icon' => 'pencil',
            'authorized' => true,
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('app.users'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('app.users_in_db', [ 'num_users' => $num_users ])<br>
            @lang('app.newest_user_is', [ 'link' => route('users.show', $latest_user), 'name' => $latest_user->name ])
        </p>
    </div>
@endsection