<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Task;
use App\Http\Requests\StoreTask;

class TasksController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index() {
        $this->authorize('list', Task::class);

		return view('tasks.index', [
			'tasks' => Task::orderBy('created_at', 'desc')
                ->get()
                ->filter(function ($value, $key) {
                    return $this->authorize('view', $value);
                })
                ->paginate()
		]);
    }

	public function store(StoreTask $request) {
        $this->authorize('create', Task::class);

        $task = new Task();
		$task->description = $request->description;
		$task->responsible = $request->responsible;
		$task->save();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been added!');		
	}
	
	public function edit(Task $task) {
        $this->authorize('update', $task);

		return view('tasks.edit', [
			'task' => $task
		]);
	}

	public function update(Task $task, StoreTask $request) {
        $this->authorize('update', $task);

		$task->description = $request->description;
		$task->responsible = $request->responsible;
		$task->save();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been updated!');		
	}

	public function delete(Task $task) {
        $this->authorize('delete', $task);

		$task->delete();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been deleted!');
	}

	public function setDone(Task $task) {
        $this->authorize('update', $task);

		$task->done_date = Carbon::now();
		$task->save();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been updated!');		
	}

	public function setUndone(Task $task) {
        $this->authorize('update', $task);

		$task->done_date = null;
		$task->save();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been updated!');		
	}

}
