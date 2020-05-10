<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Exports\CommunityVolunteers\CommunityVolunteersExport;
use App\Http\Controllers\Export\ExportableActions;
use App\Http\Requests\CommunityVolunteers\ImportCommunityVolunteers;
use App\Imports\CommunityVolunteers\CommunityVolunteersImport;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use JeroenDesloovere\VCard\VCard;
use ZipStream\ZipStream;

class ExportImportController extends BaseController
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
            'scopes' => $this->getScopes()
                ->mapWithKeys(fn ($s, $k) => [ $k => $s['label'] ])
                ->toArray(),
            'scope' => session('cmtyvol.export.scope', $this->getScopes()->keys()->first()),
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
            'scope' => [
                'required',
                Rule::in($this->getScopes()->keys()->toArray()),
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
        $scope = $this->getScopes()[$request->scope];
        return __('cmtyvol.community_volunteers') . '_' . $scope['label'] . '_' . Carbon::now()->toDateString();
    }

    protected function exportExportable(Request $request)
    {
        $columnSet = $this->getColumnSets()[$request->column_set];
        $fields = self::filterFieldsByColumnSet($this->getFields(), $columnSet);
        $scope = $this->getScopes()[$request->scope];
        $sorting = $this->getSorters()[$request->sorting];

        $export = new CommunityVolunteersExport($fields, $scope['scope']);
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
        session(['cmtyvol.export.scope' => $request->scope]);
        session(['cmtyvol.export.columnt_set' => $request->column_set]);
        session(['cmtyvol.export.sorting' => $request->sorting]);

        // Download as ZIP with portraits
        if ($request->has('include_portraits')) {
            $zip = new ZipStream($file_name . '.zip');
            $temp_file = 'temp/' . uniqid() . '.' . $file_ext;
            $export->store($temp_file);
            $zip->addFileFromPath($file_name . '.' . $file_ext, storage_path('app/' . $temp_file));
            Storage::delete($temp_file);
            $scopeMethod = $scope = $this->getScopes()[$request->scope]['scope'];
            $cmtyvols = CommunityVolunteer::{$scopeMethod}()
                ->get()
                ->load('person');
            foreach ($cmtyvols as $cmtyvol) {
                if (isset($cmtyvol->person->portrait_picture)) {
                    $picture_path = storage_path('app/'.$cmtyvol->person->portrait_picture);
                    if (is_file($picture_path)) {
                        $ext = pathinfo($picture_path, PATHINFO_EXTENSION);
                        $zip->addFileFromPath('portraits/' . $cmtyvol->person->fullName . '.' . $ext, $picture_path);
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

    public function import()
    {
        $this->authorize('import', CommunityVolunteer::class);

        return view('cmtyvol.import');
    }

    public function doImport(ImportCommunityVolunteers $request)
    {
        $this->authorize('import', CommunityVolunteer::class);

        $fields = self::getImportFields($this->getFields());

        $importer = new CommunityVolunteersImport($fields);
        $importer->import($request->file('file'));

        return redirect()
            ->route('cmtyvol.index')
            ->with('success', __('app.import_successful'));
    }

    private static function getImportFields($fields)
    {
        return collect($fields)
            ->where('overview_only', false)
            ->filter(fn ($f) => isset($f['assign']) && is_callable($f['assign']))
            ->map(fn ($f) => [
                'labels' => self::getAllTranslations($f['label_key'])
                    ->concat(isset($f['import_labels']) && is_array($f['import_labels']) ? $f['import_labels'] : [])
                    ->map(fn ($l) => strtolower($l)),
                'assign' => $f['assign'],
            ]);
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

        if ($cmtyvol->person->family_name != null || $cmtyvol->person->name != null) {
            $vcard->addName($cmtyvol->person->family_name, $cmtyvol->person->name, '', '', '');
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

        if (isset($cmtyvol->person->portrait_picture)) {
            $contents = Storage::get($cmtyvol->person->portrait_picture);
            if ($contents != null) {
                $vcard->addPhotoContent($contents);
            }
        }

        // return vcard as a download
        return $vcard->download();
    }
}
