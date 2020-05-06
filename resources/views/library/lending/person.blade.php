@extends('layouts.app')

@section('title', __('library.library') . ': ' .__('people.person'))

@section('content')
    <div id="library-app">
        <lending-person-page
            :person='@json($person)'
            person-id="{{ $person->getRouteKey() }}"
        >
            @lang('app.loading')
        </lending-person-page>
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
    $(function () {
        $('#book_id').on('change', toggleSubmit);
        toggleSubmit();

        @if($errors->get('book_id') != null)
            $('#lendBookModal').modal('show');
        @endif
        @if($errors->get('isbn') != null || $errors->get('title') != null || $errors->get('author') != null || $errors->get('language') != null)
            $('#registerBookModal').modal('show');
        @endif
    });

    $('.extend-lending-button').on('click', function () {
        var book_id = $(this).data('book');
        var days = prompt('{{ __('app.number_of_days') }}:', {{ $default_extend_duration }});
        if (days != null && days > 0) {
            window.post('{{ route('library.lending.extendBookToPerson', $person) }}', {_token: '{{ csrf_token() }}', book_id: book_id, days: days});
        }
    });
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection

@section('content-footer')

    {!! Form::open(['route' => ['library.lending.lendBookToPerson', $person], 'method' => 'post']) !!}
        @component('components.modal', [ 'id' => 'lendBookModal' ])
            @slot('title', __('library.lend_a_book'))
            {{ Form::bsAutocomplete('book_id', null, route('api.library.books.filter'), ['placeholder' => __('library.search_title_author_isbn')], '') }}
            @slot('footer')
                <button type="submit" class="btn btn-primary" id="lend-existing-book-button">
                    @icon(check) @lang('library.lend_book')
                </button>
                @can('create', App\Models\Library\LibraryBook::class)
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#registerBookModal">
                        @icon(plus-circle) @lang('library.new_book')
                    </button>
                @endcan
            @endslot
        @endcomponent
    {!! Form::close() !!}

    {!! Form::open(['route' => ['library.lending.lendBookToPerson', $person], 'method' => 'post']) !!}
        @component('components.modal', [ 'id' => 'registerBookModal' ])
            @slot('title', __('library.register_new_book'))
            {{ Form::bsText('isbn', '', [ 'placeholder' => __('library.isbn') ], '') }}
            {{ Form::bsText('title', '', [ 'placeholder' => __('app.title') ], '') }}
            {{ Form::bsText('author', '', [ 'placeholder' => __('library.author') ], '') }}
            {{ Form::bsSelect('language_code', $languages, '', [ 'placeholder' => __('app.choose_language') ], '') }}
            @slot('footer')
                {{ Form::bsSubmitButton(__('library.register_and_lend_book')) }}
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endsection
