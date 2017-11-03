@extends('layouts.app')

@section('title', 'Upload Bank Data')

@section('buttons')
    {{ Form::bsButtonLink(route('people.index'), 'Cancel', 'times-circle') }}
@endsection

@section('content')

    {!! Form::open(['route' => 'bank.doImport', 'files' => true]) !!}
        <div class="card mb-4">
            <div class="card-body">
                <div class="form-group">
                    {{ Form::file('file', null, [ 'class' => 'form-control-file'  ]) }}
                </div>
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton('Import', 'upload') }}
        </p>

    {!! Form::close() !!}
    
@endsection

