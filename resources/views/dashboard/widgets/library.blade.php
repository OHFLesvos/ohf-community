@php
    $links = [
        [
            'url' => route('library.lending.books'),
            'title' => __('library.books'),
            'icon' => 'list',
            'authorized' => true,
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('library.library'))

@section('widget-content')
    <div class="card-body">
        @lang('library.num_books_lending_to_num_persons', ['persons' => $num_borrowers, 'books' => $num_lent_books ])
    </div>
@endsection