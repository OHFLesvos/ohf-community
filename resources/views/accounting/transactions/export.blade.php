@extends('layouts.app')

@section('title', __('app.export'))

@section('content')

    {!! Form::open(['route' => ['accounting.transactions.doExport', $wallet]]) !!}
        <div class="mb-3">
            {{ Form::bsRadioList('format', $formats, $format, __('app.file_format')) }}
        </div>
        <div class="mb-3">
            {{ Form::bsRadioList('columns', $columnsSelection, $columns, __('app.columns')) }}
        </div>
        <div class="mb-3">
            {{ Form::bsRadioList('grouping', $groupings, $grouping, __('app.grouping')) }}
        </div>
        @isset($selections)
            <div class="mb-3">
                {{ Form::bsRadioList('selection', $selections, $selection, __('app.selection')) }}
            </div>
        @endisset

        <p>
            <x-form.bs-submit-button :label="__('app.export')" icon="download"/>
        </p>
    {!! Form::close() !!}

@endsection

