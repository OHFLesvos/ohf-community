<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Exports\CommunityVolunteers\CommunityVolunteersExport;
use App\Http\Controllers\Export\ExportableActions;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use JeroenDesloovere\VCard\VCard;
use ZipStream\ZipStream;

class ExportController extends BaseController
{
    use ExportableActions;

    protected function exportAuthorize()
    {
        $this->authorize('export', CommunityVolunteer::class);
    }

    protected function exportView(): string
    {
        return 'cmtyvol.export';
    }

    protected function exportViewArgs(): array
    {
        return [
            'work_statuses' => $this->getWorkStatuses()->toArray(),
            'work_status' => session('cmtyvol.export.workStatus', 'active'),
            'columnt_sets' => $this->getColumnSets()
                ->mapWithKeys(fn ($s, $k) => [ $k => $s['label'] ])
                ->toArray(),
            'columnt_set' => session('cmtyvol.export.columnt_set', $this->getColumnSets()->keys()->first()),
            'sorters' => $this->getSorters()
                ->mapWithKeys(fn ($s, $k) => [ $k => $s['label'] ])
                ->toArray(),
            'sorting' => session('cmtyvol.export.sorting', $this->getSorters()->keys()->first()),
        ];
    }

    protected function exportValidateArgs(): array
    {
        return [
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
        ];
    }

    protected function exportFilename(Request $request): string
    {
        $workStatus = $this->getWorkStatuses()->get($request->work_status);
        return __('cmtyvol.community_volunteers') . '_' . $workStatus . '_' . Carbon::now()->toDateString();
    }

    protected function exportExportable(Request $request)
    {
        $columnSet = $this->getColumnSets()[$request->column_set];
        $fields = self::filterFieldsByColumnSet($this->getFields(), $columnSet);
        $workStatus = $request->work_status;
        $sorting = $this->getSorters()[$request->sorting];

        $export = new CommunityVolunteersExport($fields, $workStatus);
        $export->orientation = $request->orientation;
        $export->sorting = $sorting['sorting'];
        if ($request->has('fit_to_page')) {
            $export->fitToWidth = 1;
            $export->fitToHeight = 1;
        }
        return $export;
    }

    private static function filterFieldsByColumnSet(array $fields, array $columnSet)
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

    protected function exportDownload(Request $request, $export, $file_name, $file_ext)
    {
        // Remember parameters in session
        session(['cmtyvol.export.work_status' => $request->work_status]);
        session(['cmtyvol.export.columnt_set' => $request->column_set]);
        session(['cmtyvol.export.sorting' => $request->sorting]);

        // Download as ZIP with portraits
        if ($request->has('include_portraits')) {
            $zip = new ZipStream($file_name . '.zip');
            $temp_file = 'temp/' . uniqid() . '.' . $file_ext;
            $export->store($temp_file);
            $zip->addFileFromPath($file_name . '.' . $file_ext, storage_path('app/' . $temp_file));
            Storage::delete($temp_file);
            $workStatus = $request->work_status;
            $cmtyvols = CommunityVolunteer::workStatus($workStatus)->get();
            foreach ($cmtyvols as $cmtyvol) {
                if (isset($cmtyvol->portrait_picture)) {
                    $picture_path = storage_path('app/'.$cmtyvol->portrait_picture);
                    if (is_file($picture_path)) {
                        $ext = pathinfo($picture_path, PATHINFO_EXTENSION);
                        $zip->addFileFromPath('portraits/' . $cmtyvol->fullName . '.' . $ext, $picture_path);
                    }
                }
            }
            $zip->finish();
        }
        // Download as simple spreadsheet
        else {
            return $export->download($file_name . '.' . $file_ext);
        }
    }

    /**
     * Download vcard
     *
     * @param  \App\Models\CommunityVolunteers\CommunityVolunteer  $communityVolunteer
     * @return \Illuminate\Http\Response
     */
    public function vcard(CommunityVolunteer $cmtyvol)
    {
        $this->authorize('view', $cmtyvol);

        // define vcard
        $vcard = new VCard();
        // if ($communityVolunteer->company != null) {
        //     $vcard->addCompany($communityVolunteer->company);
        // }
        $vcard->addCompany(config('app.name'));

        if ($cmtyvol->family_name != null || $cmtyvol->first_name != null) {
            $vcard->addName($cmtyvol->family_name, $cmtyvol->first_name, '', '', '');
        }
        if ($cmtyvol->email != null) {
            $vcard->addEmail($cmtyvol->email);
        }
        if ($cmtyvol->local_phone != null) {
            $vcard->addPhoneNumber(preg_replace('/[^+0-9]/', '', $cmtyvol->local_phone), 'HOME');
        }
        if ($cmtyvol->whatsapp != null && $cmtyvol->local_phone != $cmtyvol->whatsapp) {
            $vcard->addPhoneNumber(preg_replace('/[^+0-9]/', '', $cmtyvol->whatsapp), 'WORK');
        }

        if (isset($cmtyvol->portrait_picture)) {
            $contents = Storage::get($cmtyvol->portrait_picture);
            if ($contents != null) {
                $vcard->addPhotoContent($contents);
            }
        }

        // return vcard as a download
        return $vcard->download();
    }
}
