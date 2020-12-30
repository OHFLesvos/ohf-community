@extends('widgets.base', [
    'icon' => 'shopping-bag',
    'title' => __('shop.shop'),
    'href' => route('shop.index'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('Cards redeemed today') => $redeemed_cards,

        ],
    ])
@endsection
