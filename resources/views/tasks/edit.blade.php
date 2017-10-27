@extends('layouts.app')

@section('title', 'Edit Task')

@section('buttons')
    <a href="{{ route('tasks.delete', $task) }}" class="delete-conformation btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
    <a href="{{ route('tasks.index') }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i> Cancel</a>
@endsection

@section('content')

	{!! Form::model($task, ['route' => ['tasks.update', $task]]) !!}
		<div class="card">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
						<div class="form-group">
							{{ Form::label('description') }}
							{{ Form::text('description', null, [ 'class' => 'form-control'.($errors->has('description') ? ' is-invalid' : ''), 'autofocus' ]) }}
                            @if ($errors->has('description'))
                                <span class="invalid-feedback">{{ $errors->first('description') }}</span>
                            @endif                            
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							{{ Form::label('responsible') }}
							{{ Form::text('responsible', null, [ 'class' => 'form-control'.($errors->has('responsible') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('responsible'))
                                <span class="invalid-feedback">{{ $errors->first('responsible') }}</span>
                            @endif
						</div>
					</div>
				</div>
				@if ($task->done_date != null)
					<p>Marked as done: {{ $task->done_date }}</p>
				@endif
			</div>
		</div>
		<br>
		<p>
			<small class="pull-right text-sm text-right text-muted">
				Created: {{ $task->created_at }}<br>
				Last updated: {{ $task->updated_at }}
			</small>
			{{ Form::button('<i class="fa fa-check"></i> Update', [ 'type' => 'submit', 'name' => 'update', 'class' => 'btn btn-primary' ]) }}
		</p>
	{!! Form::close() !!}
	
@endsection

@section('script')
    $(function(){
	   $('.delete-conformation').on('click', function(){
          return confirm('Really delete this task?'); 
       });
    });
@endsection
