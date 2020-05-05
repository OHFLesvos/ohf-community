@extends('library.layouts.library')

@section('title', __('library.library'))

@section('wrapped-content')
    <div id="library-app">
        <lending-page>
            @lang('app.loading')
        </lending-page>
    </div>
@endsection

@section('script')
    function navigateToBook() {
        if ($('#book_id').val()) {
            document.location='/library/lending/book/' + $('#book_id').val();
        }
    }
    $(function () {
        $('#book_id').on('change', navigateToBook);

        $('#button-register-person').on('click', function () {
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
            @slot('title', __('people.register_new_person'))
            <div class="form-row">
                <div class="col-md">
                    {{ Form::bsText('name', '', [ 'placeholder' => __('people.name') ], '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsText('family_name', '', [ 'placeholder' => __('people.family_name') ], '') }}
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
                    {{ Form::bsText('nationality', null, [ 'placeholder' => __('people.nationality') ], '') }}
                </div>
                <div class="col-md">
                    {{ Form::bsNumber('police_no', null, ['prepend' => '05/', 'placeholder' => __('people.police_number') ], '') }}
                </div>
            </div>
            @slot('footer')
                {{ Form::bsSubmitButton(__('app.register')) }}
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection
