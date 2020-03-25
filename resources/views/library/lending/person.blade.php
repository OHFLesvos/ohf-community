@extends('layouts.app')

@section('title', __('library.library') . ': ' .__('people.person'))

@section('content')

    <h2 class="mb-3">
        {{ $person->fullName }}
        <small class="d-block d-sm-inline">
            {{ $person->nationality }}@if(isset($person->nationality) && isset($person->date_of_birth)),@endif {{ $person->date_of_birth }}
        </small>
    </h2>

    @if($lendings->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('library.book')</th>
                        <th class="d-none d-sm-table-cell">@lang('library.lent')</th>
                        <th>@lang('library.return')</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lendings as $lending)
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
                                <form action="{{ route('library.lending.returnBookFromPerson', $person) }}" method="post" class="d-inline">
                                    {{ csrf_field() }}
                                    {{ Form::hidden('book_id', $lending->book->id) }}
                                    <button type="submit" class="btn btn-sm btn-success">
                                        @icon(inbox)<span class="d-none d-sm-inline"> @lang('library.return')</span>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-primary extend-lending-button" data-book="{{ $lending->book->id }}">
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
            @lang('library.no_books_lent')
        @endcomponent
    @endif
    @if(!Setting::has('library.max_books_per_person') || Setting::get('library.max_books_per_person') > $lendings->count())
        <p>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lendBookModal">
                @icon(plus-circle) @lang('library.lend_a_book')
            </button>
        </p>
    @endif

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
            {{ Form::bsAutocomplete('book_id', null, route('library.books.filter'), ['placeholder' => __('library.search_title_author_isbn')], '') }}
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
            {{ Form::bsText('language', '', [ 'placeholder' => __('app.language') ], '') }}
            @slot('footer')
                {{ Form::bsSubmitButton(__('library.register_and_lend_book')) }}
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endsection
