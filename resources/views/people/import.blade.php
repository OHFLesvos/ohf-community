@extends('layouts.app')

@section('title', 'People')

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(['route' => 'people.doImport', 'files' => true]) !!}
    <div class="panel panel-primary">
        <div class="panel-heading">Upload data</div>
        <div class="panel-body">
                <div class="form-group">
                    {{ Form::label('file') }}
                    {{ Form::file('file', null, [ 'class' => 'form-control'  ]) }}
                </div>
        </div>
    </div>
    <p>
        {{ Form::submit('Import', [ 'name' => 'add', 'class' => 'btn btn-primary' ]) }} &nbsp;
        <a href="{{ route('people.index') }}" class="btn btn-default">Cancel</a>
    </p>
    {!! Form::close() !!}
    
@endsection

