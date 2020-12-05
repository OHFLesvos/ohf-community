@extends('layouts.app')

@section('title', __('people.create_code_card'))

@section('content')
    @component('components.alert.info')
        @lang('people.code_card_unique')
    @endcomponent
    {{ Form::open(['route' => 'bank.createCodeCard']) }}
        {{ Form::bsNumber('amount', 10, ['min' => 1], __('app.amount'), 'Number of code cards to generate') }}
        <p>
            {{ Form::bsSubmitButton('@icon(file-pdf-o) ' . __('people.create_pdf'), 'create') }}
        </p>
    {{ Form::close() }}
    <div id="patience-notice" style="display:none">
        @component('components.alert.info')
            @lang('people.creating_code_card_be_patient')
        @endcomponent
    </div>
@endsection

@section('script')
    $(function () {
        $('form').on('submit', function () {
            $('#patience-notice').fadeIn();
        });
    });
@endsection
