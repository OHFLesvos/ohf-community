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
		return Task::latest()
				->where('done_date', null)
				->get()
				->filter(function ($value, $key) {
                     return $this->authorize('view', $value);
                 });
    }

    public function store(StoreTask $request)
    {
		$task = Task::create([ 'description' => request('description') ]);
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
