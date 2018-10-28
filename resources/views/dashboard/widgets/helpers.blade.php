@php
    $links = [
        [
            'url' => route('people.helpers.index'),
            'title' => __('app.manage'),
            'icon' => 'pencil',
            'authorized' => true,
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('people.helpers'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('people.we_have_n_helpers', [ 
                'active' => $active,
                'trial' => $trial,
            ])<br>
            @lang('people.n_helpers_on_waiting_list', [ 
                'num' => $applicants,
            ])<br>
        </p>
    </div>
@endsection