@extends('layouts.app')

@section('title', __('cmtyvol.import_data'))

@section('content')

    {!! Form::open(['route' => 'cmtyvol.doImport', 'files' => true]) !!}
        {{ Form::bsFile('file', [ 'accept' => '.xlsx,.xls,.csv', 'class' => 'import-form-file' ], __('app.choose_file')) }}
        <table class="import-form-header-mapping table d-none" data-query="{{ route('api.cmtyvol.getHeaderMappings') }}">
            <thead>
                <th>@lang('app.field_to_import')</th>
                <th>@lang('app.field_in_database')</th>
                <th>@lang('app.add_to_existing_values')</th>
            </thead>
            <tbody></tbody>
        </table>
        <p>
            <x-form.bs-submit-button :label="__('app.import')" icon="upload"/>
        </p>
    {!! Form::close() !!}

@endsection
