<?php

namespace App\Navigation\Drawer\Logviewer;

use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Gate;

class LogViewerNavigationItem extends BaseNavigationItem {

    protected $route = 'logviewer.index';

    protected $caption = 'logviewer.logviewer';

    protected $icon = 'file-alt';

    protected $active = 'logviewer*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-logs');
    }

}
