@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Export'))

@section('content')

    {!! Form::open(['route' => ['accounting.transactions.doExport', $wallet]]) !!}
        <div class="mb-3">
            {{ Form::bsRadioList('format', $formats, $format, __('File format')) }}
        </div>
        <div class="mb-3">
            {{ Form::bsRadioList('columns', $columnsSelection, $columns, __('Columns')) }}
        </div>
        <div class="mb-3">
            {{ Form::bsRadioList('grouping', $groupings, $grouping, __('Grouping')) }}
        </div>
        @isset($selections)
            <div class="mb-3">
                {{ Form::bsRadioList('selection', $selections, $selection, __('Selection')) }}
            </div>
        @endisset

        <p>
            <x-form.bs-submit-button :label="__('Export')" icon="download"/>
        </p>
    {!! Form::close() !!}

@endsection

