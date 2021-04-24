@extends('widgets.base', [
    'icon' => 'id-badge',
    'title' => __('app.community_volunteers'),
    'href' => route('cmtyvol.index'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('Active community volunteers') => $active,
        ],
    ])
@endsection
