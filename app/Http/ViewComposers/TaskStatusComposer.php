<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class TaskStatusComposer {

	/**
     * Create the composer.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('num_open_tasks',  \App\Task::open()->count());
    }
}
