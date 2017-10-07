@extends('layouts.app')

@section('title', 'User Profile')

@section('content')

	<h1 class="display-4">User Profile</h1>
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
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
	
    {!! Form::open(['route' => ['userprofile.update']]) !!}
		<div class="card">
			<div class="card-body">
			
				<div class="form-group">
					{{ Form::label('name', 'Name') }}
					{{ Form::text('name', $user->name, [ 'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
					@if ($errors->has('name'))
						<span class="invalid-feedback">{{ $errors->first('name') }}</span>
					@endif
				</div>

				<div class="form-group">
					{{ Form::label('email', 'E-Mail Address') }}
					{{ Form::text('email', $user->email, [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required' ]) }}
					@if ($errors->has('email'))
						<span class="invalid-feedback">{{ $errors->first('email') }}</span>
					@endif
				</div>
				
			</div>
		</div>
		<br>
		<p>
			{{ Form::submit('Update', [ 'name' => 'update', 'class' => 'btn btn-primary' ]) }} &nbsp;
		</p>
    {!! Form::close() !!}
    
@endsection
