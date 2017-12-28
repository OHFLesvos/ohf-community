<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Task;
use App\Http\Resources\TaskResource;
use App\Http\Requests\StoreTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TasksController extends ParentController {

    public function __construct()
    {
		$this->authorizeResource(Task::class);
    }

    public function index()
    {
		return Task::where('user_id', Auth::id())
				->where('done_date', null)
				//->latest()
				->get()
				->filter(function ($value, $key) {
					return $this->authorize('view', $value);
				});
	}
	
	public function show(Task $task)
    {
		return $task;
    }

    public function store(StoreTask $request)
    {
		$task = new Task();
		$task->description = request('description');
		Auth::user()->tasks()->save($task);
		return response()->json($task, 201);
	}
	
	public function update(Task $task, StoreTask $request)
	{
		$task->description = $request->description;
		$task->save();
		return response(null, 204);
	}

	public function destroy(Task $task)
	{
        $task->delete();
        return response(null, 204);
    }

	public function done(Task $task)
	{
		$this->authorize('update', $task);

		$task->done_date = Carbon::now();
		$task->save();
		return response(null, 204);
	}

}
