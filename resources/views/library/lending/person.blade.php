@extends('layouts.app')

@section('title', __('library.library') . ': ' .__('people.person'))

@section('content')

    <h2 class="mb-3">
        {{ $person->fullName }}
        <small class="d-block d-sm-inline">{{ $person->nationality }}@isset($person->date_of_birth), {{ $person->date_of_birth }}@endisset
        </small>
    </h2>

    @php
        $lendings = $person->bookLendings()
            ->whereNull('returned_date')
            ->orderBy('return_date', 'asc');
    @endphp
    @if($lendings->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('library.book')</th>
                        <th class="d-none d-sm-table-cell">@lang('library.lending')</th>
                        <th>@lang('library.return')</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lendings->get() as $lending)
                        <tr class="@if($lending->return_date->lt(Carbon\Carbon::today()))table-danger @elseif($lending->return_date->eq(Carbon\Carbon::today()))table-warning @endif">
                            <td class="align-middle">
                                <a href="{{ route('library.lending.book', $lending->book) }}">{{ $lending->book->title }} @isset($lending->book->author)({{ $lending->book->author }})@endisset</a>
                            </td>
                            <td class="align-middle d-none d-sm-table-cell">
                                {{ $lending->lending_date->toDateString() }}
                            </td>
                            <td class="align-middle">
                                {{ $lending->return_date->toDateString() }}
                            </td>
                            <td class="fit">
                                <form action="{{ route('library.lending.returnBook', $person) }}" method="post" class="d-inline">
                                    {{ csrf_field() }}
                                    {{ Form::hidden('book_id', $lending->book->id) }}
                                    <button type="submit" class="btn btn-sm btn-success">
                                        @icon(inbox)<span class="d-none d-sm-inline"> @lang('library.return')</span>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-primary">
                                    @icon(calendar-plus-o)<span class="d-none d-sm-inline"> @lang('library.extend')</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.info')
            @lang('library.no_books_lent_so_far')
        @endcomponent
    @endif

    <div class="card mt-3">
        <div class="card-header">@lang('library.lend_a_book')</div>
        <div class="card-body">
            {!! Form::open(['route' => ['library.lending.lendBook', $person], 'method' => 'post']) !!}
                {{ Form::bsAutocomplete('book_id', null, route('library.books.filter'), ['placeholder' => __('library.search_title_author_isbn')], '') }}
                <button type="submit" class="btn btn-primary" id="lend-existing-book-button">@icon(check) @lang('library.lend_book')</button>                
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('script')
    function toggleSubmit() {
        if ($('#book_id').val()) {
            $('#lend-existing-book-button').attr('disabled', false);
        } else {
            $('#lend-existing-book-button').attr('disabled', true);
        }
    }
    $(function(){
        $('#book_id').on('change', toggleSubmit);
        toggleSubmit();
    });
@endsection
