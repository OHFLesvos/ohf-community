@extends('widgets.base', [
    'icon' => 'users',
    'title' => __('people.people'),
    'href' => route('people.index'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('People registered in the database') => $num_people,
            __('New people registered today') => $num_people_added_today,

        ],
    ])
@endsection
