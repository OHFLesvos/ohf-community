@extends('layouts.app', ['wide_layout' => false])

@section('title', __('people.import_export_persons'))

@section('content')
    <div class="row">
        @can('create', App\Models\People\Person::class)
            <div class="col-md">
                {!! Form::open(['route' => 'people.import', 'files' => true]) !!}
                    <div class="card shadow-sm">
                        <div class="card-header">@lang('app.import')</div>
                        <div class="card-body pb-2">
                            {{ Form::bsFile('file', [ 'accept' => '.xlsx,.xls,.csv' ], __('app.choose_file')) }}
                        </div>
                        <div class="card-footer text-right">
                            <x-form.bs-submit-button :label="__('app.import')" icon="upload"/>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        @endcan
        @can('export', App\Models\People\Person::class)
            <div class="col-md">
                <div class="card shadow-sm">
                    <div class="card-header">@lang('app.export')</div>
                        <div class="card-body">
                            <a href="{{ route('people.export') }}" class="btn btn-primary">
                            <x-icon icon="download"/>
                            @lang('app.download')
                        </a>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection

