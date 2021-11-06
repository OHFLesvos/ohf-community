@extends('widgets.base', [
    'icon' => 'door-open',
    'title' => __('Visitors'),
    'href' => route('visitors.index'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('Total visitors today') => $todays_visitors,
        ],
    ])
@endsection
