@php
    $links = [
        [
            'url' => route('people.index'),
            'title' => __('app.manage'),
            'icon' => 'pencil',
            'authorized' => true,
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('people.people'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('people.there_are_n_people_registered', [ 
                'num' => $num_people 
            ])@if($num_people_added_today > 0) (@lang('people.n_new_today', [ 
                'num' => $num_people_added_today 
            ]))@endif.
        </p>
    </div>
@endsection