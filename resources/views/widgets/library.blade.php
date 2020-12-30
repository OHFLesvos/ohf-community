@extends('widgets.base', [
    'icon' => 'book',
    'title' => __('library.library'),
    'href' => route('library.index'),
])

@section('widget-content')
    @include('widgets.value-table', [
        'items' => [
            __('Current borrowers') => $num_borrowers,
            __('Currently lent books') => $num_lent_books,
            __('Books in the database') => $num_books,
        ],
    ])
@endsection


