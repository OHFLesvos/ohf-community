@extends('layouts.app')

@section('title', 'Edit person')

@section('content')

    {!! Form::model($person, ['route' => ['people.update', $person], 'method' => 'put']) !!}
    <div class="card">
        <div class="card-body">
			<div class="form-row">
				<div class="col">
					<div class="form-group">
						{{ Form::label('name') }}
						{{ Form::text('name', null, [ 'class' => 'form-control', 'autofocus' ]) }}
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						{{ Form::label('family_name') }}
						{{ Form::text('family_name', null, [ 'class' => 'form-control' ]) }}
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col">
					<div class="form-group">
						{{ Form::label('case_no') }}
						{{ Form::number('case_no', null, [ 'class' => 'form-control' ]) }}
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						{{ Form::label('nationality') }}
						{{ Form::text('nationality', null, [ 'class' => 'form-control' ]) }}
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col">
					<div class="form-group">
						{{ Form::label('languages') }}
						{{ Form::text('languages', null, [ 'class' => 'form-control' ]) }}
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						{{ Form::label('skills') }}
						{{ Form::text('skills', null, [ 'class' => 'form-control' ]) }}
					</div>
				</div>
			</div>
            <div class="form-group">
                {{ Form::label('remarks') }}
                {{ Form::text('remarks', null, [ 'class' => 'form-control' ]) }}
            </div>
        </div>
    </div>
	<br>
    <p>
		<small class="pull-right text-sm text-right text-muted">
			Registered: {{ $person->created_at }}<br>Updated: {{ $person->updated_at }}
		</small>
        {{ Form::submit('Update', [ 'name' => 'update', 'class' => 'btn btn-primary' ]) }} &nbsp;
        {{ Form::submit('Delete', [ 'name' => 'delete', 'class' => 'btn btn-danger', 'id' => 'delete' ]) }} &nbsp;
        <a href="{{ route('people.index') }}" class="btn btn-secondary">Cancel</a>
    </p>
    {!! Form::close() !!}
    
@endsection

@section('script')
    $(function(){
       $('#delete').on('click', function(){
          return confirm('Really delete this person?'); 
       });
    });
@endsection
