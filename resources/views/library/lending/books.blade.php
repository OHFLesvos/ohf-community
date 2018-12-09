@extends('layouts.app')

@section('title', __('library.library') . ': ' . ucfirst(__('library.lent_books')))

@section('content')

    @if($books->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('library.book')</th>
                        <th class="d-none d-sm-table-cell">@lang('people.person')</th>
                        <th>@lang('library.return')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>
                                <a href="{{ route('library.lending.book', $book) }}">{{ $book->title }}</a>
                            </td>
                            @php
                                $lending = $book->lendings()->whereNull('returned_date')->first();
                            @endphp
                            <td class="d-none d-sm-table-cell">
                                @isset($lending)@isset($lending->person)
                                    <a href="{{ route('library.lending.person', $lending->person) }}">{{ $lending->person->fullName}}</a>
                                @endisset @endisset
                            </td>
                            <td>
                                @isset($lending){{ $lending->return_date->toDateString() }}@endisset
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.info')
            @lang('library.no_books_lent')
        @endcomponent
    @endif

@endsection
