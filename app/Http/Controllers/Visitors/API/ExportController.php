<?php

namespace App\Http\Controllers\Visitors\API;

use App\Exports\Visitors\Sheets\VisitorCheckInsExport;
use App\Exports\Visitors\Sheets\VisitorDataExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExportController extends Controller
{
    use ExportableActions;

    protected function exportAuthorize(): void
    {
        $this->authorize('export-visitors');
    }

    protected function exportViewArgs(): array
    {
        return [];
    }

    protected function exportValidateArgs(): array
    {
        return [
            'type' => [
                'required',
                Rule::in(['visitors', 'checkins']),
            ],
        ];
    }

    protected function exportFilename(Request $request): string
    {
        if ($request->type == 'checkins') {
            $prefix = __('Check-ins');
        } else {
            $prefix = __('Visitors');
        }

        return $prefix.' as of '.now()->toDateString();
    }

    protected function exportExportable(Request $request)
    {
        if ($request->type == 'checkins') {
            return new VisitorCheckInsExport();
        }

        return new VisitorDataExport();
    }
}
