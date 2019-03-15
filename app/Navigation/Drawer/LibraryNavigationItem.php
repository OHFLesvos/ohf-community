<?php

namespace App\Navigation\Drawer;

use Illuminate\Support\Facades\Gate;

class LibraryNavigationItem extends BaseNavigationItem {

    protected $route = 'library.lending.index';

    protected $caption = 'library.library';

    protected $icon = 'book';

    protected $active = 'library*';

    public function isAuthorized(): bool
    {
        return Gate::allows('operate-library');
    }

}
