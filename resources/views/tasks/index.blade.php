@extends('layouts.app')

@section('title', 'Tasks')

@section('buttons')
    @can('create', App\Task::class)
        @if (count($errors) == 0)
            <button class="btn btn-primary" id="create-task-button"><i class="fa fa-plus-circle"></i> Add</button>
        @endif
    @endcan
@endsection

@section('content')

	<div class="card mb-4" id="create-task-container" @if (count($errors) == 0) style="display: none;" @endif>
		<div class="card-header">
			New Task
		</div>
		<div class="card-body">
			{!! Form::open(['route' => 'tasks.store']) !!}
			<div class="form-row">
				<div class="col-md">
					<div class="form-group">
						{{ Form::text('description', null, [ 'class' => 'form-control'.($errors->has('description') ? ' is-invalid' : ''), 'id' => 'description', 'placeholder' => 'Description', 'autofocus'  ]) }}
                        @if ($errors->has('description'))
                            <span class="invalid-feedback">{{ $errors->first('description') }}</span>
                        @endif
					</div>
				</div>
				<div class="col-md">
					<div class="form-group">
						{{ Form::text('responsible', Auth::user()->name, [ 'class' => 'form-control'.($errors->has('responsible') ? ' is-invalid' : ''), 'placeholder' => 'Responsible' ]) }}
                        @if ($errors->has('responsible'))
                            <span class="invalid-feedback">{{ $errors->first('responsible') }}</span>
                        @endif
					</div>
				</div>
				<div class="col-md-auto">
					{{ Form::button('<i class="fa fa-check"></i> Save', [ 'type' => 'submit', 'name' => 'add', 'class' => 'btn btn-primary' ]) }} &nbsp;
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>

	@if( ! $tasks->isEmpty() )
        <table class="table table-sm table-bordered" id="results-table">
            <thead>
                <tr>
                    <th style="width:20px"></th>
                    <th>Description</th>
                    <th>Responsible</th>
                    <th class="d-none d-md-table-cell">Created</th>
                    <!--<th style="width:180px">Due</th>-->
                    <th class="d-none d-md-table-cell">Done</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr @if ($task->done_date != null) class="table-success" @endif>
                        <td class="align-middle">
                            @if ($task->done_date != null)
                                @can('update', $task)
                                    <a href="{{ route('tasks.setUndone', $task) }}" class="btn btn-success btn-sm">
                                @endcan
                                    <i class="fa fa-check"></i>
                                @can('update', $task)
                                    </a>
                                @endcan
                            @else
                                @can('update', $task)
                                    <a href="{{ route('tasks.setDone', $task) }}" class="btn btn-outline-success btn-sm">
                                @endcan
                                    <i class="fa fa-check"></i>
                                @can('update', $task)
                                    </a>
                                @endcan
                            @endif
                        </td>
                        <td class="align-middle">
                            @can('update', $task)
                                <a href="{{ route('tasks.edit', $task) }}" title="Edit">
                            @endcan
                                {{ $task->description }}
                            @can('update', $task)
                                </a>
                            @endcan
                        </td>
                        <td class="align-middle">{{ $task->responsible }}</td>
                        <td class="align-middle d-none d-md-table-cell">{{ $task->created_at }}</td>
                        <!-- <td class="align-middle">{{ $task->due_date }}</td> -->
                        <td class="align-middle d-none d-md-table-cell">{{ $task->done_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tasks->links('vendor.pagination.bootstrap-4') }}
	@else
		<div class="alert alert-info">
            <i class="fa fa-info-circle"></i> No tasks found.
        </div>
	@endif
	
@endsection

@section('script')
    $(function(){
		$('#create-task-button').on('click', function(){
			$(this).parent().hide();
			$('#create-task-container').show();
			$('#description').focus();
	   });
    });
@endsection
