@extends('layouts.app')

@section('title', __('people.create_code_card'))

@section('content')
    <x-alert type="info">
        @lang('people.code_card_unique')
    </x-alert>
    {{ Form::open(['route' => 'bank.createCodeCard']) }}
        {{ Form::bsNumber('amount', 10, ['min' => 1], __('app.amount'), 'Number of code cards to generate') }}
        <p>
            {{ Form::bsSubmitButton(__('people.create_pdf'), 'create') }}
        </p>
    {{ Form::close() }}
    <div id="patience-notice" style="display:none">
        <x-alert type="info">
            @lang('people.creating_code_card_be_patient')
        </x-alert>
    </div>
@endsection

@section('script')
    $(function () {
        $('form').on('submit', function () {
            $('#patience-notice').fadeIn();
        });
    });
@endsection
