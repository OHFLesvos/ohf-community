<?php

namespace App\Navigation;

use Illuminate\Support\Facades\Gate;

class LogViewerNavigationItem extends BaseNavigationItem {

    protected $route = 'logviewer.index';

    protected $caption = 'app.logviewer';

    protected $icon = 'file-text-o';

    protected $active = 'logviewer*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-logs');
    }

}
