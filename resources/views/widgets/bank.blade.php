@extends('widgets.base', [
    'icon' => 'university',
    'title' => __('bank.bank'),
    'href' => route('bank.withdrawal.search'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('Persons served today') => $persons,
            __('Coupons handed out today') => $coupons,

        ],
    ])
@endsection
