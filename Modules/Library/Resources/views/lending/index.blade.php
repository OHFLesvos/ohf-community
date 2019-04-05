@extends('library::layouts.library')

@section('title', __('library::library.library'))

@section('wrapped-content')
    <div class="row">
        <div class="col-md">
            <div class="card mb-4">
                <div class="card-header">{{ ucfirst(__('people::people.persons')) }}
                    <a href="{{ route('library.lending.persons') }}" class="pull-right">@lang('library::library.borrowers') ({{ $num_borrowers }})</a>
                </div>
                <div class="card-body pb-2">
                    {{ Form::bsAutocompleteWithButton('person_id', null, route('people.filterPersons'), ['placeholder' => __('people::people.search_existing_person')], __('app.register'), 'button-register-person', 'plus-circle') }}
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-header">@lang('library::library.books')
                    <a href="{{ route('library.lending.books') }}" class="pull-right">@lang('library::library.lent_books') ({{ $num_lent_books }})</a>
                </div>
                <div class="card-body pb-2">
                    {{ Form::bsAutocomplete('book_id', null, route('library.books.filter'), ['placeholder' => __('library::library.search_title_author_isbn')], '') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    function navigateToPerson() {
        if ($('#person_id').val()) {
            document.location='/library/lending/person/' + $('#person_id').val();
        }
    }
    function navigateToBook() {
        if ($('#book_id').val()) {
            document.location='/library/lending/book/' + $('#book_id').val();
        }
    }
    $(function(){
        $('#person_id').on('change', navigateToPerson);
        $('#book_id').on('change', navigateToBook);

        $('#button-register-person').on('click', function(){
            $('#registerPersoModal').modal('show');
        });
        $('#registerPersoModal').on('shown.bs.modal', function (e) {
            $('input[name="name"]').focus();
        });
        @if($errors->get('name') != null || $errors->get('family_name') != null || $errors->get('gender') != null || $errors->get('date_of_birth') != null  || $errors->get('nationality') != null || $errors->get('police_no') != null)
            $('#registerPersoModal').modal('show');
        @endif        
    });
@endsection

@section('content-footer')

    {!! Form::open(['route' => ['library.lending.storePerson'], 'method' => 'post']) !!}
        @component('components.modal', [ 'id' => 'registerPersoModal' ])
            @slot('title', __('people::people.register_new_person'))
            <div class="form-row">
                <div class="col-md">
                    {{ Form::bsText('name', '', [ 'placeholder' => __('people::people.name') ], '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsText('family_name', '', [ 'placeholder' => __('people::people.family_name') ], '') }}
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    {{ Form::genderSelect('gender', null, '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsStringDate('date_of_birth', null, [ 'rel' => 'birthdate', 'data-age-element' => 'age' ], '') }}
                </div>
            </div>
            <div class="form-row">
                <div class="col-md">
                    {{ Form::bsText('nationality', null, [ 'placeholder' => __('people::people.nationality') ], '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsNumber('police_no', null, ['prepend' => '05/', 'placeholder' => __('people::people.police_number') ], '') }}
                </div>
            </div>
            @slot('footer')
                {{ Form::bsSubmitButton(__('app.register')) }}
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endsection
