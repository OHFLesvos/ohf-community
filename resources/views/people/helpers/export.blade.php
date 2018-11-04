@extends('layouts.app')

@section('title', __('people.exportieren_helper_data'))

@section('content')

    {!! Form::open(['route' => 'people.helpers.doExport']) !!}
        <div class="mb-3">
            {{ Form::bsRadioList('format', $formats, $format, __('app.file_format')) }}
        </div>
        <div class="mb-3">
            {{ Form::bsRadioList('scope', $scopes, $scope, __('people.scope')) }}
        </div>
        {{ Form::bsCheckbox('include_portraits', 1, null, __('people.include_portraits')) }}<br>
        <p>
            {{ Form::bsSubmitButton(__('app.export'), 'download') }}
        </p>
    {!! Form::close() !!}
    
@endsection

