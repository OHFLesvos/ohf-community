@php
    $links = [
        [
            'url' => route('shop.index'),
            'title' => __('shop::shop.scan_card'),
            'icon' => 'qrcode',
            'authorized' => true,
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('shop::shop.shop'))

@section('widget-content')
    <div class="card-body">
        {!! trans_choice('shop::shop.num_cards_redeemed_today', $redeemed_cards, ['num' => $redeemed_cards]) !!}
    </div>
@endsection