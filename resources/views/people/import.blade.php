@extends('layouts.app')

@section('title', 'Upload People Data')

@section('content')

    {!! Form::open(['route' => 'people.doImport', 'files' => true]) !!}
        <div class="card mb-4">
            <div class="card-body">
                    <div class="form-group">
                        {{ Form::file('file', null, [ 'class' => 'form-control-file'  ]) }}
                    </div>
            </div>
        </div>

        {{ Form::submit('Import', [ 'name' => 'add', 'class' => 'btn btn-primary' ]) }} &nbsp;
        <a href="{{ route('people.index') }}" class="btn btn-secondary">Cancel</a>
    {!! Form::close() !!}
    
@endsection

