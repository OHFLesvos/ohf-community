@extends('dashboard.widgets.base')

@section('widget-title', __('library.library'))

@section('widget-content')
    <div class="card-body">
        @lang('library.num_books_lending_to_num_persons', ['persons' => $num_borrowers, 'books' => $num_lent_books ])
        @lang('library.num_books_in_total', ['books' => $num_books])
    </div>
@endsection


