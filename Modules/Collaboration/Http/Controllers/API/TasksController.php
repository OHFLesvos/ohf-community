<?php

namespace Modules\Collaboration\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\Collaboration\Entities\Task;
use Modules\Collaboration\Transformers\TaskResource;
use Modules\Collaboration\Http\Requests\StoreTask;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class TasksController extends Controller {

    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), ['done' => 'update']);
    }

    public function index()
    {
        $tasks = Task::open()
                ->withOwner(Auth::id())
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
        $task->description = $request->description;
        $task->user()->associate(Auth::user());
        $task->save();
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
        $task->done_date = Carbon::now();
        $task->save();
        return response(null, 204);
    }

}