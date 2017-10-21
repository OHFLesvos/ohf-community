@extends('layouts.app')

@section('title', 'Tasks')

@section('content')

	<h1 class="display-4">Tasks</h1>
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

	@if (count($errors) == 0)
	<p><button class="btn btn-primary" id="create-task-button"><i class="fa fa-plus"></i> Add new task</button></p>
	@endif
	<div class="card" id="create-task-container" @if (count($errors) == 0) style="display: none;" @endif>
		<div class="card-header">
			New Task
		</div>
		<div class="card-body">
			{!! Form::open(['route' => 'tasks.store']) !!}
			<div class="row">
				<div class="col-md">
					<div class="form-group">
						{{ Form::text('description', null, [ 'class' => 'form-control', 'id' => 'description', 'placeholder' => 'Description'  ]) }}
					</div>
				</div>
				<div class="col-md">
					<div class="form-group">
						{{ Form::text('responsible', null, [ 'class' => 'form-control', 'placeholder' => 'Responsible' ]) }}
					</div>
				</div>
				<div class="col-md-auto">
					{{ Form::submit('Add', [ 'name' => 'add', 'class' => 'btn btn-primary' ]) }} &nbsp;
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
	<br>
	
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
                                <a href="{{ route('tasks.setUndone', $task) }}" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a>
                            @else
                                <a href="{{ route('tasks.setDone', $task) }}" class="btn btn-outline-success btn-sm"><i class="fa fa-check"></i></a>
                            @endif
                        </td>
                        <td class="align-middle"><a href="{{ route('tasks.edit', $task) }}" title="Edit">{{ $task->description }}</a></td>
                        <td class="align-middle">{{ $task->responsible }}</td>
                        <td class="align-middle d-none d-md-table-cell">{{ $task->created_at }}</td>
                        <!-- <td class="align-middle">{{ $task->due_date }}</td> -->
                        <td class="align-middle d-none d-md-table-cell">{{ $task->done_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
	@else
		<div class="alert alert-info">
            No tasks found.
        </div>
	@endif
	
@endsection

@section('script')
    $(function(){
		$('#description').focus();
		$('#create-task-button').on('click', function(){
			$(this).parent().hide();
			$('#create-task-container').show();
			$('#description').focus();
	   });
    });
@endsection
