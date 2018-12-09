@extends('layouts.app')

@section('title', __('library.library') . ': ' .__('library.book'))

@section('content')

    <h2 class="mb-3">
        {{ $book->title }}
        <small class="d-block d-sm-inline">{{ $book->author }}@isset($book->isbn), {{ $book->isbn13 }}@endisset
        </small>
    </h2>

    @php
        $lending = $book->lendings()->whereNull('returned_date')->first();
    @endphp
    @isset($lending)
        @component('components.alert.info')
            Book is lent to <a href="{{ route('library.lending.person', $lending->person) }}">{{ $lending->person->fullName}}</a> until <strong>{{ $lending->return_date->toDateString() }}</strong>.
        @endcomponent
        {!! Form::open(['route' => ['library.lending.returnBook', $book], 'method' => 'post']) !!}
            <p>
                <button type="submit" class="btn btn-success">
                    @icon(inbox)<span class="d-none d-sm-inline"> @lang('library.return')</span>
                </button>
            </p>
        {!! Form::close() !!}
    @else
        @component('components.alert.success')
            @lang('library.book_is_available')
        @endcomponent
        <div class="card mt-3">
            <div class="card-header">@lang('library.lend_book')</div>
            <div class="card-body">
                {!! Form::open(['route' => ['library.lending.lendBook', $book], 'method' => 'post']) !!}
                    {{ Form::bsAutocomplete('person_id', null, route('people.filterPersons'), ['placeholder' => __('people.search_existing_person')], '') }}
                    <button type="submit" class="btn btn-primary" id="lend-existing-book-button">@icon(check) @lang('library.lend_book')</button>                
                {!! Form::close() !!}
            </div>
        </div>
    @endisset

@endsection

@section('script')
    function toggleSubmit() {
        if ($('#person_id').val()) {
            $('#lend-existing-book-button').attr('disabled', false);
        } else {
            $('#lend-existing-book-button').attr('disabled', true);
        }
    }
    $(function(){
        $('#person_id').on('change', toggleSubmit);
        toggleSubmit();
    });
@endsection
