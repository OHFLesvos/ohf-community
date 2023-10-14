<?php

namespace App\View\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class SystemInfoWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('view-system-information');
    }

    public function key(): string
    {
        return 'system';
    }

    public function data(): array
    {
        return [
            'OS' => PHP_OS_FAMILY,
            'Hostname' => gethostname(),
            'Web server' => request()->server('SERVER_SOFTWARE'),
            'PHP' => phpversion(),
            'Database' => sprintf('%s (%s)', DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME), DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION)),
            'Laravel' => app()->version(),
            'Environment' => app()->environment(),
        ];
    }
}
