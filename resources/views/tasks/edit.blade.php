@extends('layouts.app')

@section('title', 'Edit Task')

@section('buttons')
	@can('delete', $task)
        {{ Form::bsDeleteForm(route('tasks.destroy', $task), 'Delete', 'trash', 'Really delete this task?') }}
    @endcan
    {{ Form::bsButtonLink(route('tasks.index'), 'Cancel', 'times-circle') }}
@endsection

@section('content')

	{!! Form::model($task, ['route' => ['tasks.update', $task]]) !!}

		<div class="card mb-4">
			<div class="card-body">

				<div class="form-row">
					<div class="col-md">
						{{ Form::bsText('description', null, [ 'required', 'autofocus' ]) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('responsible', null, [ 'required' ]) }}
					</div>
				</div>
				@if ($task->done_date != null)
					<p>Marked as done: {{ $task->done_date }}</p>
				@endif

			</div>
		</div>

		<p>
			<small class="pull-right text-sm text-right text-muted">
				Created: {{ $task->created_at }}<br>
				Last updated: {{ $task->updated_at }}
			</small>
            {{ Form::bsSubmitButton('Update') }}
		</p>

	{!! Form::close() !!}
	
@endsection
