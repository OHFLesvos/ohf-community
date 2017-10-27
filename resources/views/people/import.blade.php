@extends('layouts.app')

@section('title', 'Upload People Data')

@section('buttons')
    <a href="{{ route('people.index') }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i> Cancel</a>
@endsection

@section('content')

    {!! Form::open(['route' => 'people.doImport', 'files' => true]) !!}
        <div class="card mb-4">
            <div class="card-body">
                    <div class="form-group">
                        {{ Form::file('file', null, [ 'class' => 'form-control-file'  ]) }}
                    </div>
            </div>
        </div>

        {{ Form::button('<i class="fa fa-upload"></i> Import', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }} &nbsp;
    {!! Form::close() !!}
    
@endsection

