@extends('widgets.base', [
    'icon' => 'donate',
    'title' => __('Donation Management'),
    'href' => route('fundraising.index'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('Registered donors') => $num_donors,
            __('Donations this month') => $num_donations_month,
            __('Donations this year') => $num_donations_year,
            __('Donations in total') => $num_donations_total,
        ],
    ])
@endsection
