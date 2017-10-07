<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
		return view('tasks.index', [
			'tasks' => Task::orderBy('created_at', 'desc')
				->paginate()
		]);
    }

	public function store(StoreTask $request) {
        $task = new Task();
		$task->description = $request->description;
		$task->responsible = $request->responsible;
		$task->save();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been added!');		
	}
	
	public function edit(Task $task) {
		return view('tasks.edit', [
			'task' => $task
		]);
	}

	public function update(Task $task, StoreTask $request) {
		$task->description = $request->description;
		$task->responsible = $request->responsible;
		$task->save();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been updated!');		
	}

	public function delete(Task $task) {
		$task->delete();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been deleted!');
	}

	public function setDone(Task $task) {
		$task->done_date = Carbon::now();
		$task->save();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been updated!');		
	}

	public function setUndone(Task $task) {
		$task->done_date = null;
		$task->save();

		return redirect()->route('tasks.index')
				->with('success', 'Task has been updated!');		
	}

}
