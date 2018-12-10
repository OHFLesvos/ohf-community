@extends('layouts.app')

@section('title', __('library.library') . ': ' .__('library.book'))

@section('content')

    <h2 class="mb-3">
        {{ $book->title }}
        <small class="d-block d-sm-inline">
            {{ $book->author }}@if(isset($book->author) && isset($book->isbn13)),@endif {{ $book->isbn }}
        </small>
    </h2>

    @php
        $lending = $book->lendings()->whereNull('returned_date')->first();
    @endphp
    @isset($lending)
        @if($lending->return_date->lt(Carbon\Carbon::today()))
            @component('components.alert.error')
                @lang('library.book_is_overdue')
            @endcomponent
        @elseif($lending->return_date->eq(Carbon\Carbon::today()))
            @component('components.alert.warning')
                @lang('library.book_is_overdue_soon')
            @endcomponent
        @endif
        @component('components.alert.info')
            @lang('library.book_is_lent_to_person_until', [ 'route' => route('library.lending.person', $lending->person), 'person' => $lending->person->fullName, 'until' => $lending->return_date->toDateString() ])
        @endcomponent
        {!! Form::open(['route' => ['library.lending.returnBook', $book], 'method' => 'post']) !!}
            <p>
                <button type="submit" class="btn btn-success">
                    @icon(inbox) @lang('library.return')
                </button>
                <button type="button" class="btn btn-primary extend-lending-button">
                    @icon(calendar-plus-o) @lang('library.extend')
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

    $('.extend-lending-button').on('click', function(){
        var days = prompt('{{ __('app.number_of_days') }}:', {{ $default_extend_duration }});
        if (days != null && days > 0) {
            window.post('{{ route('library.lending.extendBook', $book) }}', {_token: '{{ csrf_token() }}', days: days});
        }
    });
@endsection
