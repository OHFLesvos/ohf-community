@extends('layouts.app')

@section('title', 'Bank')

@section('content')

	<h1 class="display-4">Register person</h1>
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

    {!! Form::open(['route' => 'bank.store']) !!}
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
			<div class="form-group">
				{{ Form::label('remarks') }}
				{{ Form::text('remarks', null, [ 'class' => 'form-control' ]) }}
			</div>
			<div class="form-group">
				{{ Form::label('value', 'Transaction') }}
				{{ Form::number('value', $transaction_value, [ 'class' => 'form-control', 'style' => 'width:80px', 'min' => 0, 'max' => $transaction_value ]) }}
			</div>
        </div>
    </div>
	<br>
    <p>
        {{ Form::submit('Add', [ 'name' => 'add', 'class' => 'btn btn-primary' ]) }} &nbsp;
        <a href="{{ route('bank.index') }}" class="btn btn-secondary">Cancel</a>
    </p>
    {!! Form::close() !!}
    
@endsection

@section('script')
@endsection
