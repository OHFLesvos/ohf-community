@extends('layouts.app')

@section('title', __('library::library.library') . ': ' .__('library::library.book'))

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
                @lang('library::library.book_is_overdue')
            @endcomponent
        @elseif($lending->return_date->eq(Carbon\Carbon::today()))
            @component('components.alert.warning')
                @lang('library::library.book_is_overdue_soon')
            @endcomponent
        @endif
        @component('components.alert.info')
            @lang('library::library.book_is_lent_to_person_until', [ 'route' => route('library.lending.person', $lending->person), 'person' => $lending->person->fullName, 'until' => $lending->return_date->toDateString() ])
        @endcomponent
        {!! Form::open(['route' => ['library.lending.returnBook', $book], 'method' => 'post']) !!}
            <p>
                <button type="submit" class="btn btn-success">
                    @icon(inbox) @lang('library::library.return')
                </button>
                <button type="button" class="btn btn-primary extend-lending-button">
                    @icon(calendar-plus-o) @lang('library::library.extend')
                </button>
            </p>
        {!! Form::close() !!}
    @else
        @component('components.alert.success')
            @lang('library::library.book_is_available')
        @endcomponent
        <p>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lendBookModal">
                @icon(plus-circle) @lang('library::library.lend_book')
            </button>
        </p>
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

        @if($errors->get('person_id') != null)
            $('#lendBookModal').modal('show');
        @endif
    });

    $('.extend-lending-button').on('click', function(){
        var days = prompt('{{ __('app.number_of_days') }}:', {{ $default_extend_duration }});
        if (days != null && days > 0) {
            window.post('{{ route('library.lending.extendBook', $book) }}', {_token: '{{ csrf_token() }}', days: days});
        }
    });
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection

@section('content-footer')
    {!! Form::open(['route' => ['library.lending.lendBook', $book], 'method' => 'post']) !!}
        @component('components.modal', [ 'id' => 'lendBookModal' ])
            @slot('title', __('library::library.lend_book'))
            {{ Form::bsAutocomplete('person_id', null, route('people.filterPersons'), ['placeholder' => __('people.search_existing_person')], '') }}
            @slot('footer')
                <button type="submit" class="btn btn-primary" id="lend-existing-book-button">@icon(check) @lang('library::library.lend_book')</button>                
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endsection
