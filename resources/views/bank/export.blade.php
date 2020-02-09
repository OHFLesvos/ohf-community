@extends('layouts.app')

@section('title', __('app.export'))

@section('content')

    {!! Form::open(['route' => 'bank.doExport']) !!}
        <div class="card mb-4">
            <div class="card-body">
                {{ Form::bsRadioInlineList('format', $formats, $selectedFormat, __('app.file_format')) }}
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.export')) }}
        </p>
    {!! Form::close() !!}
    
@endsection

