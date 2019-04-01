<?php

namespace Modules\Tasks\Http\Controllers;

use App\Http\Controllers\ParentController;

use Modules\Tasks\Entities\Task;
use Modules\Tasks\Http\Resources\TaskResource;
use Modules\Tasks\Http\Requests\StoreTask;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class TasksController extends ParentController {

    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
                ->where('done_date', null)
                //->latest()
                ->get()
                ->filter(function ($value, $key) {
                    return $this->authorize('view', $value);
                });
        return TaskResource::collection($tasks);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function store(StoreTask $request)
    {
        $task = new Task();
        $task->description = request('description');
        Auth::user()->tasks()->save($task);
        return response()->json(new TaskResource($task), 201);
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
