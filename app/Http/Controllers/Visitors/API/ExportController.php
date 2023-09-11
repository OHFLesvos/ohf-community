<?php

namespace App\Http\Controllers\Visitors\API;

use App\Exports\Visitors\Sheets\VisitorCheckInsExport;
use App\Exports\Visitors\Sheets\VisitorDataExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use Carbon\Carbon;
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
            'date_from' => [
                'required_if:type,checkins',
                'date',
                'before_or_equal:date_to',
            ],
            'date_to' => [
                'required_if:type,checkins',
                'date',
                'after_or_equal:date_from',
            ],
        ];
    }

    protected function exportFilename(Request $request): string
    {
        if ($request->type == 'checkins') {
            return __('Check-ins :from - :to', ['from' => $request->date_from, 'to' => $request->date_to]);
        }

        return __('Visitor as of :date', ['date' => now()->toDateString()]);
    }

    protected function exportExportable(Request $request)
    {
        if ($request->type == 'checkins') {
            return new VisitorCheckInsExport(new Carbon($request->date_from), new Carbon($request->date_to));
        }

        return new VisitorDataExport();
    }
}
