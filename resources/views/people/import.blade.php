@extends('layouts.app', ['wide_layout' => false])

@section('title', __('people.import_people_data'))

@section('content')

    {!! Form::open(['route' => 'people.doImport', 'files' => true]) !!}
        {{ Form::bsFile('file', [ 'accept' => '.xlsx,.xls,.csv' ], __('app.choose_file')) }}
        <p>
            <x-form.bs-submit-button :label="__('app.import')" icon="upload"/>
        </p>
    {!! Form::close() !!}

@endsection

