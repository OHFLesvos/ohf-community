@extends('layouts.app')

@section('title', __('app.import'))

@section('content')
    {!! Form::open(['route' => 'fundraising.donations.doImport', 'files' => true]) !!}
        <div class="mb-3">
            {{ Form::bsRadioList('type', $types, $type, __('app.type')) }}
        </div>
        {{ Form::bsFile('file', [ 'accept' => '.xlsx,.xls,.csv' ], __('app.choose_file')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.import'), 'upload') }}
        </p>
    {!! Form::close() !!}
@endsection

