@extends('layouts.app')

@section('title', 'Tasks')

@section('content')

	<h1 class="display-4">Edit Task</h1>
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

	{!! Form::model($task, ['route' => ['tasks.update', $task]]) !!}
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col">
						<div class="form-group">
							{{ Form::label('description') }}
							{{ Form::text('description', null, [ 'class' => 'form-control', 'id' => 'description' ]) }}
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							{{ Form::label('responsible') }}
							{{ Form::text('responsible', null, [ 'class' => 'form-control' ]) }}
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
		<p>
			{{ Form::submit('Update', [ 'name' => 'update', 'class' => 'btn btn-primary' ]) }} &nbsp;
			<a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
		</p>
	{!! Form::close() !!}
	
@endsection

@section('script')
    $(function(){
       $('#description').select();
    });
@endsection
