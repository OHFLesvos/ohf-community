<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Exports\CommunityVolunteers\CommunityVolunteersExport;
use App\Exports\PageOrientation;
use App\Http\Controllers\CommunityVolunteers\BaseController;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use ZipStream\ZipStream;

class ExportController extends BaseController
{
    private function getFormats(): array
    {
        // File extension => Label
        return [
            'xlsx' => __('Excel (.xlsx)'),
            'csv' => __('Comma-separated values (.csv)'),
            'pdf' => __('PDF (.pdf)'),
        ];
    }

    /**
     * Prepare and download export as file.
     */
    public function doExport(Request $request)
    {
        $this->authorize('export', CommunityVolunteer::class);

        $request->validate([
            'format' => [
                'required',
                Rule::in(array_keys($this->getFormats())),
            ],
            'work_status' => [
                'required',
                Rule::in($this->getWorkStatuses()->keys()),
            ],
            'column_set' => [
                'required',
                Rule::in($this->getColumnSets()->keys()->toArray()),
            ],
            'sorting' => [
                'required',
                Rule::in($this->getSorters()->keys()->toArray()),
            ],
            'orientation' => [
                'required',
                'in:portrait,landscape',
            ],
            'fit_to_page' => 'boolean',
            'include_portraits' => 'boolean',
        ]);

        return $this->downloadExportable(
            $request,
            $this->createExportable($request),
            $this->getExportFilename($request),
            $request->format
        );
    }

    private function createExportable(Request $request): CommunityVolunteersExport
    {
        $columnSet = $this->getColumnSets()->toArray()[$request->column_set];
        $fields = $this->filterFieldsByColumnSet($this->getFields(), $columnSet);
        $workStatus = $request->work_status;
        $sorting = $this->getSorters()[$request->sorting];

        $export = new CommunityVolunteersExport($fields, $workStatus);
        $export->orientation = $request->orientation == 'portrait' ? PageOrientation::Portrait : PageOrientation::Landscape;
        $export->sorting = $sorting['sorting'];
        if ($request->has('fit_to_page') && $request->input('fit_to_page') == true) {
            $export->fitToWidth = 1;
            $export->fitToHeight = 1;
        }

        return $export;
    }

    private function filterFieldsByColumnSet(array $fields, array $columnSet): Collection
    {
        return collect($fields)
            ->where('overview_only', false)
            ->where('exclude_export', false)
            ->filter(fn ($e) => ! isset($e['authorized_view']) || $e['authorized_view'])
            ->filter(function ($e) use ($columnSet) {
                if (count($columnSet['columns']) > 0) {
                    if (isset($e['form_name'])) {
                        return in_array($e['form_name'], $columnSet['columns']);
                    }

                    return false;
                }

                return true;
            });
    }

    private function getExportFilename(Request $request): string
    {
        $workStatus = $this->getWorkStatuses()->get($request->work_status);

        return __('Community Volunteers').'_'.$workStatus.'_'.Carbon::now()->toDateString();
    }

    private function downloadExportable(
        Request $request,
        CommunityVolunteersExport $export,
        string $file_name,
        string $file_ext
    ) {
        // Remember parameters in session
        session(['cmtyvol.export.work_status' => $request->work_status]);
        session(['cmtyvol.export.columnt_set' => $request->column_set]);
        session(['cmtyvol.export.sorting' => $request->sorting]);

        // Download as simple spreadsheet
        if ($request->missing('include_portraits') || ! $request->input('include_portraits')) {
            return $export->download($file_name.'.'.$file_ext);
        }

        \Debugbar::disable(); // Debugbar will inject additional code at end of zip file if enabled

        // Download as ZIP with portraits
        $zip = new ZipStream(outputName: $file_name.'.zip', sendHttpHeaders: true);
        $temp_file = 'temp/'.uniqid().'.'.$file_ext;
        $export->store($temp_file);
        $zip->addFileFromPath($file_name.'.'.$file_ext, storage_path('app/'.$temp_file));
        Storage::delete($temp_file);
        $workStatus = $request->work_status;
        $cmtyvols = CommunityVolunteer::query()
            ->workStatus($workStatus)
            ->whereNotNull('portrait_picture')
            ->get();
        foreach ($cmtyvols as $cmtyvol) {
            $picture_path = storage_path('app/'.$cmtyvol->portrait_picture);
            if (is_file($picture_path)) {
                $ext = pathinfo($picture_path, PATHINFO_EXTENSION);
                $zip->addFileFromPath('portraits/'.$cmtyvol->full_name.'.'.$ext, $picture_path);
            }
        }
        $zip->finish();
    }
}
