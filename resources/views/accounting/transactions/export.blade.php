@extends('layouts.app')

@section('title', __('app.export'))

@section('content')

    {!! Form::open(['route' => 'accounting.transactions.doExport']) !!}
        <div class="mb-3">
            {{ Form::bsRadioList('format', $formats, $format, __('app.file_format')) }}
        </div>
        <div class="mb-3">
            {{ Form::bsRadioList('grouping', $groupings, $grouping, __('app.grouping')) }}
        </div>        
        <p>
            {{ Form::bsSubmitButton(__('app.export'), 'download') }}
        </p>
    {!! Form::close() !!}
    
@endsection

