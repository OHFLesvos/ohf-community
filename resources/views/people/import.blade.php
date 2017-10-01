@extends('layouts.app')

@section('title', 'People')

@section('content')

	<h1 class="display-4">Upload data</h1>
	<br>

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
    <div class="card">
        <div class="card-body">
                <div class="form-group">
                    {{ Form::label('file') }}
                    {{ Form::file('file', null, [ 'class' => 'form-control-file'  ]) }}
                </div>
        </div>
    </div>
	<br>
    <p>
        {{ Form::submit('Import', [ 'name' => 'add', 'class' => 'btn btn-primary' ]) }} &nbsp;
        <a href="{{ route('people.index') }}" class="btn btn-secondary">Cancel</a>
    </p>
    {!! Form::close() !!}
    
@endsection

