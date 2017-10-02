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
	<!--
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
	-->

	{!! Form::open(['route' => 'tasks.store']) !!}
		<div class="card">
			<div class="card-header">
				New Task
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col">
						<div class="form-group">
							{{ Form::text('description', null, [ 'class' => 'form-control', 'id' => 'description', 'placeholder' => 'Description'  ]) }}
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							{{ Form::text('responsible', null, [ 'class' => 'form-control', 'placeholder' => 'Responsible' ]) }}
						</div>
					</div>
					<div class="col-auto">
						{{ Form::submit('Add', [ 'name' => 'add', 'class' => 'btn btn-primary' ]) }} &nbsp;
					</div>
				</div>
			</div>
		</div>
	{!! Form::close() !!}
	<br>
	
	@if( ! $tasks->isEmpty() )
    <table class="table table-sm" id="results-table">
        <thead>
            <tr>
				<th style="width:20px"></th>
                <th>Description</th>
                <th>Responsible</th>
                <th style="width:180px">Created</th>
                <!--<th style="width:180px">Due</th>-->
                <th style="width:180px">Done</th>
                <th style="width:55px"></th>
            </tr>
        </thead>
        <tbody>
			@foreach ($tasks as $task)
				<tr @if ($task->done_date != null) class="table-success" @endif>
					<td>
						@if ($task->done_date != null)
							<a href="{{ route('tasks.setUndone', $task) }}" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a>
						@else
							<a href="{{ route('tasks.setDone', $task) }}" class="btn btn-outline-success btn-sm"><i class="fa fa-check"></i></a>
						@endif
					</td>
					<td class="align-middle">{{ $task->description }}</td>
					<td class="align-middle">{{ $task->responsible }}</td>
					<td class="align-middle">{{ $task->created_at }}</td>
					<!-- <td class="align-middle">{{ $task->due_date }}</td> -->
					<td class="align-middle">{{ $task->done_date }}</td>
					<td class="align-middle">
						<a href="{{ route('tasks.edit', $task) }}" title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;
						<a href="{{ route('tasks.delete', $task) }}" title="Delete" class="delete-conformation"><i class="fa fa-trash"></i></a>
					</td>
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
	   $('.delete-conformation').on('click', function(){
          return confirm('Really delete this task?'); 
       });
    });
@endsection
