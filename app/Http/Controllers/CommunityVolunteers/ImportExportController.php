<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Exports\CommunityVolunteers\CommunityVolunteersExport;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use JeroenDesloovere\VCard\VCard;
use ZipStream\ZipStream;
use ZipStream\Option\Archive;
use App\Http\Requests\CommunityVolunteers\ImportCommunityVolunteers;
use App\Imports\CommunityVolunteers\CommunityVolunteersImport;
use App\Imports\CommunityVolunteers\HeadingRowImport;
use App\Models\ImportFieldMapping;

class ImportExportController extends BaseController
{
    public function index()
    {
        return view('cmtyvol.import-export', [
            'formats' => $this->getFormats(),
            'format' => array_keys($this->getFormats())[0],
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
        ]);
    }

    private function getFormats()
    {
        // File extension => Label
        return [
            'xlsx' => __('Excel (.xlsx)'),
            'csv' => __('Comma-separated values (.csv)'),
            'pdf' => __('PDF (.pdf)'),
        ];
    }

    public function doImport(ImportCommunityVolunteers $request)
    {
        $this->authorize('import', CommunityVolunteer::class);

        $fields = self::getImportFields($this->getFields());

        if ($request->map != null) {
            collect($request->map)->each(fn ($m) => ImportFieldMapping::updateOrCreate([
                'model' => 'cmtyvol',
                'from' => $m['from'],
            ], [
                'to' => $m['to'],
                'append' => isset($m['append']),
            ]));

            $fields = collect($request->map)->filter(fn ($m) => $m['to'] != null)
                ->map(fn ($m) => [
                    'key' => $m['to'],
                    'labels' => collect([ strtolower($m['from']) ]),
                    'append' => isset($m['append']),
                    'assign' => $fields->firstWhere('key', $m['to'])['assign'],
                    'get' => $fields->firstWhere('key', $m['to'])['get'],
                ]);
        }

        $importer = new CommunityVolunteersImport($fields);
        $importer->import($request->file('file'));

        return redirect()
            ->route('cmtyvol.index')
            ->with('success', __('Import successful.'));
    }

    private static function getImportFields($fields)
    {
        return collect($fields)
            ->where('overview_only', false)
            ->filter(fn ($f) => isset($f['assign']) && is_callable($f['assign']))
            ->map(fn ($f) => [
                'key' => $f['label'],
                'labels' => collect($f['label'])
                    ->concat(isset($f['import_labels']) && is_array($f['import_labels']) ? $f['import_labels'] : [])
                    ->map(fn ($l) => strtolower($l)),
                'append' => false,
                'assign' => $f['assign'],
                'get' => $f['value'],
            ]);
    }

    public function getHeaderMappings(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $table_headers = collect((new HeadingRowImport)->toArray($request->file('file'))[0][0]);

        $fields = self::getImportFields($this->getFields());

        $variations = $fields->mapWithKeys(
            fn ($f) =>
            $f['labels']->mapWithKeys(fn ($l) => [ $l => $f['key'] ])
        );

        $cached_mappings = ImportFieldMapping::model('cmtyvol')
            ->whereIn('from', $table_headers)
            ->get();

        $defaults = $table_headers->mapWithKeys(fn ($f) => [
            $f => $cached_mappings->contains('from', $f) ? [
                'value' => $cached_mappings->firstWhere('from', $f)['to'],
                'append' => $cached_mappings->firstWhere('from', $f)['append'],
            ] : [
                'value' => $variations->get(strtolower($f)),
                'append' => false,
            ],
        ]);

        $available = collect([ null => '-- ' . __("don't import") . ' --' ])
            ->merge($fields->mapWithKeys(fn ($f) => [ $f['key'] => __($f['key']) ]));

        return [ 'headers' => $table_headers, 'available' => $available, 'defaults' => $defaults ];
    }

    /**
     * Prepare and download export as file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
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

    private function createExportable(Request $request)
    {
        $columnSet = $this->getColumnSets()[$request->column_set];
        $fields = $this->filterFieldsByColumnSet($this->getFields(), $columnSet);
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

    private function filterFieldsByColumnSet(array $fields, array $columnSet)
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
        return __('Community Volunteers') . '_' . $workStatus . '_' . Carbon::now()->toDateString();
    }

    private function downloadExportable(Request $request, $export, string $file_name, string $file_ext)
    {
        // Remember parameters in session
        session(['cmtyvol.export.work_status' => $request->work_status]);
        session(['cmtyvol.export.columnt_set' => $request->column_set]);
        session(['cmtyvol.export.sorting' => $request->sorting]);

        // Download as ZIP with portraits
        if ($request->has('include_portraits')) {
            $options = new Archive();
            $options->setSendHttpHeaders(true);
            $zip = new ZipStream($file_name . '.zip', $options);
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
