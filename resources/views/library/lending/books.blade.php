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
                        @php
                            $lending = $book->lendings()->whereNull('returned_date')->first();
                        @endphp
                        <tr class="@if($lending->return_date->lt(Carbon\Carbon::today()))table-danger @elseif($lending->return_date->eq(Carbon\Carbon::today()))table-warning @endif">
                            <td>
                                <a href="{{ route('library.lending.book', $book) }}">
                                    {{ $book->title }}@isset($book->author), {{ $book->author }}@endisset
                                </a>
                            </td>
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
