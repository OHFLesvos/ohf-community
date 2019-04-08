@php
    $links = [
        [
            'url' => route('shop.barber.index'),
            'title' => __('app.view_list'),
            'icon' => 'list',
            'authorized' => true,
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('shop::shop.barber_shop'))

@section('widget-content')
    <div class="card-body">
        {!! trans_choice('shop::shop.num_reservations_today', $num_reservations, ['num' => $num_reservations]) !!}
    </div>
@endsection