<?php

namespace App\Http\Controllers\Visitors\API;

use App\Exports\Visitors\VisitorsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use Illuminate\Http\Request;

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
        ];
    }

    protected function exportFilename(Request $request): string
    {
        return  __('Visitors').' as of '.now()->toDateString();
    }

    protected function exportExportable(Request $request)
    {
        $request->validate([
        ]);

        return new VisitorsExport();
    }
}
