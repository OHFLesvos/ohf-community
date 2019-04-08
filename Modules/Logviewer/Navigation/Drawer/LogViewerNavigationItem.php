<?php

namespace Modules\Logviewer\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Gate;

class LogViewerNavigationItem extends BaseNavigationItem {

    protected $route = 'logviewer.index';

    protected $caption = 'logviewer::logviewer.logviewer';

    protected $icon = 'file-text-o';

    protected $active = 'logviewer*';

    public function isAuthorized(): bool
    {
        return Gate::allows('view-logs');
    }

}
