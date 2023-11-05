<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PDO;

class SystemInfoController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $this->authorize('view-system-information');

        return response()
            ->json([
                'OS' => PHP_OS_FAMILY,
                'Hostname' => gethostname(),
                'Web server' => request()->server('SERVER_SOFTWARE'),
                'PHP' => phpversion(),
                'Database' => sprintf('%s (%s)', DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME), DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION)),
                'Laravel' => app()->version(),
                'Environment' => app()->environment(),
            ]);
    }
}
