@php
    $links = [
        [
            'url' => route($num_lent_books > 0 ? 'library.lending.books' : 'library.lending.index'),
            'title' => __($num_lent_books > 0 ? 'library::library.books' : 'app.search'),
            'icon' => 'list',
            'authorized' => true,
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('library::library.library'))

@section('widget-content')
    <div class="card-body">
        @lang('library::library.num_books_lending_to_num_persons', ['persons' => $num_borrowers, 'books' => $num_lent_books ])
        @lang('library::library.num_books_in_total', ['books' => $num_books])
    </div>
@endsection