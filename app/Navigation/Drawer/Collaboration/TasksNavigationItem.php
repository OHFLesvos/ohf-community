<?php

namespace App\Navigation\Drawer\Collaboration;

use App\Navigation\Drawer\BaseNavigationItem;

use App\Models\Collaboration\Task;

use Illuminate\Support\Facades\Auth;

class TasksNavigationItem extends BaseNavigationItem {

    protected $route = 'tasks';

    protected $caption = 'tasks.tasks';

    protected $icon = 'tasks';

    protected $active = 'tasks*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', Task::class);
    }

    public function getBadge()
    {
        $num_open_tasks = Auth::user()->tasks()->open()->count();
        return $num_open_tasks > 0 ? $num_open_tasks : null;
    }

}
