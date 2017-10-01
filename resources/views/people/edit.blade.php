@extends('layouts.app')

@section('title', 'Edit person')

@section('content')

	<h1 class="display-4">Edit person</h1>
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

    {!! Form::model($person, ['route' => ['people.update', $person], 'method' => 'put']) !!}
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                {{ Form::label('name') }}
                {{ Form::text('name', null, [ 'class' => 'form-control', 'id' => 'name'  ]) }}
            </div>
            <div class="form-group">
                {{ Form::label('family_name') }}
                {{ Form::text('family_name', null, [ 'class' => 'form-control' ]) }}
            </div>
            <div class="form-group">
                {{ Form::label('case_no') }}
                {{ Form::number('case_no', null, [ 'class' => 'form-control' ]) }}
            </div>
            <div class="form-group">
                {{ Form::label('nationality') }}
                {{ Form::text('nationality', null, [ 'class' => 'form-control' ]) }}
            </div>
            <div class="form-group">
                {{ Form::label('languages') }}
                {{ Form::text('languages', null, [ 'class' => 'form-control' ]) }}
            </div>
            <div class="form-group">
                {{ Form::label('skills') }}
                {{ Form::text('skills', null, [ 'class' => 'form-control' ]) }}
            </div>
            <div class="form-group">
                {{ Form::label('remarks') }}
                {{ Form::text('remarks', null, [ 'class' => 'form-control' ]) }}
            </div>
        </div>
    </div>
	<br>
    <p>
        {{ Form::submit('Update', [ 'name' => 'update', 'class' => 'btn btn-primary' ]) }} &nbsp;
        {{ Form::submit('Delete', [ 'name' => 'delete', 'class' => 'btn btn-danger', 'id' => 'delete' ]) }} &nbsp;
        <a href="{{ route('people.index') }}" class="btn btn-secondary">Cancel</a>
    </p>
    {!! Form::close() !!}
    
@endsection

@section('script')
    $(function(){
       $('#name').focus();
       $('#delete').on('click', function(){
          return confirm('Really delete this person?'); 
       });
    });
@endsection
