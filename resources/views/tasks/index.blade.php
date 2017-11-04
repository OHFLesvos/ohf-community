@extends('layouts.app')

@section('title', 'Tasks')

@section('buttons')
@endsection

@section('content')

    @can('create', App\Task::class)
        <div class="card mb-4" id="create-task-container">
            <div class="card-header">
                New Task
            </div>
            <div class="card-body">
                {!! Form::open(['route' => 'tasks.store']) !!}
                <div class="form-row">
                    <div class="col-md-8">
                        {{ Form::bsText('description', null, [ 'required', 'placeholder' => 'Description' ], '') }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('responsible', Auth::user()->name, [ 'required', 'placeholder' => 'Responsible' ], '') }}
                    </div>
                    <div class="col-md-auto">
                        {{ Form::bsSubmitButton('Create') }}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endcan

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
                                @icon(check)
                                @can('update', $task)
                                    </a>
                                @endcan
                            @else
                                @can('update', $task)
                                    <a href="{{ route('tasks.setDone', $task) }}" class="btn btn-outline-success btn-sm">
                                @endcan
                                @icon(check)
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
        @component('components.alert.info')
            No tasks found.
        @endcomponent
	@endif
	
@endsection
