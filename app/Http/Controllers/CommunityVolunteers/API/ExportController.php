<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Exports\CommunityVolunteers\CommunityVolunteersExport;
use App\Exports\PageOrientation;
use App\Http\Controllers\Controller;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use ZipStream\ZipStream;

class ExportController extends Controller
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

    protected function getFields(): array
    {
        return [
            [
                'label' => __('First Name'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->first_name,
            ],
            [
                'label' => __('Family Name'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->family_name,
            ],
            [
                'label' => __('Nickname'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->nickname,
            ],
            [
                'label' => __('Nationality'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->nationality,
            ],
            [
                'label' => __('Gender'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->gender != null ? ($cmtyvol->gender == 'f' ? __('Female') : __('Male')) : null,
            ],
            [
                'label' => __('Date of birth'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->date_of_birth,
            ],
            [
                'label' => __('Age'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->age,
            ],
            [
                'label' => __('Police Number'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->police_no,
            ],
            [
                'label' => __('Languages'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->languages != null ? implode(', ', $cmtyvol->languages) : null,
            ],
            [
                'label' => __('Local Phone'),
                'value' => 'local_phone',
            ],
            [
                'label' => __('Other Phone'),
                'value' => 'other_phone',
            ],
            [
                'label' => __('WhatsApp'),
                'value' => 'whatsapp',
            ],
            [
                'label' => __('Email address'),
                'value' => 'email',
            ],
            [
                'label' => __('Skype'),
                'value' => 'skype',
            ],
            [
                'label' => __('Residence'),
                'value' => 'residence',
            ],
            [
                'label' => __('Pickup location'),
                'value' => 'pickup_location',
            ],
            [
                'label' => __('Responsibilities'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->responsibilities
                    ->map(fn (Responsibility $r) => [
                        'value' => $r->name,
                        'from' => $r->getRelationValue('pivot')->start_date,
                        'to' => $r->getRelationValue('pivot')->end_date,
                    ]),
                'value_export' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->responsibilities()
                    ->orderBy('start_date')
                    ->get()
                    ->map(fn (Responsibility $r) => [
                        'value' => $r->name,
                        'from' => $r->getRelationValue('pivot')->start_date,
                        'to' => $r->getRelationValue('pivot')->end_date,
                    ])
                    ->pluck('value')
                    ->implode('; '),
            ],
            [
                'label' => __('Starting Date'),
                'value' => fn (CommunityVolunteer $cmtyvol) => optional($cmtyvol->first_work_start_date)->toDateString(),
            ],
            [
                'label' => __('Leaving Date'),
                'value' => fn (CommunityVolunteer $cmtyvol) => optional($cmtyvol->last_work_end_date)->toDateString(),
            ],
            [
                'label' => __('Working since (days)'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->working_since_days,
            ],
            [
                'label' => __('Notes'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->notes,
            ],
            [
                'label' => __('Comments'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->comments
                    ->sortBy('created_at')
                    ->pluck('content')
                    ->implode('; '),
            ],
        ];
    }

    protected function getWorkStatuses(): Collection
    {
        return collect([
            'active' => __('Active').' ('.CommunityVolunteer::workStatus('active')->count().')',
            'future' => __('Future').' ('.CommunityVolunteer::workStatus('future')->count().')',
            'alumni' => __('Alumni').' ('.CommunityVolunteer::workStatus('alumni')->count().')',
        ]);
    }

    protected function getGroupings(): Collection
    {
        return collect([
            'nationalities' => [
                'label' => __('Nationalities'),
                'groups' => fn () => CommunityVolunteer::nationalities(true),
                'query' => fn (Builder $query, $value) => $query->where('nationality', $value),
            ],
            'languages' => [
                'label' => __('Languages'),
                'groups' => fn () => CommunityVolunteer::languages(true),
                'query' => fn (Builder $query, $value) => $query->where('languages', 'like', '%"'.$value.'"%'),
            ],
            'gender' => [
                'label' => __('Gender'),
                'groups' => fn () => CommunityVolunteer::genders(true),
                'query' => fn (Builder $query, $value) => $query->where('gender', $value),
                'label_transform' => fn ($groups) => collect($groups)
                    ->map(function ($s) {
                        switch ($s) {
                            case 'f':
                                return __('Female');
                            case 'm':
                                return __('Male');
                            default:
                                return $s;
                        }
                    }),
            ],
            'responsibilities' => [
                'label' => __('Responsibilities'),
                'groups' => fn () => Responsibility::has('communityVolunteers')
                    ->orderBy('name')
                    ->pluck('name')
                    ->toArray(),
                'query' => fn ($q, $v) => $q->whereHas('responsibilities', fn (Builder $query) => $query->where('name', $v)),
            ],
            'pickup_locations' => [
                'label' => __('Pickup locations'),
                'groups' => fn () => CommunityVolunteer::pickupLocations(true),
                'query' => fn (Builder $query, $value) => $query->where('pickup_location', $value),
            ],
        ]);
    }

    protected function getColumnSets(): Collection
    {
        return collect([
            'all' => [
                'label' => __('All'),
                'columns' => [],
            ],
            'name_nationality_occupation' => [
                'label' => __('Nationality, Occupation'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'responsibilities'],
            ],
            'contact_info' => [
                'label' => __('Contact information'),
                'columns' => ['name', 'family_name', 'nickname', 'local_phone', 'other_phone', 'whatsapp', 'email', 'skype', 'residence'],
            ],
            'name_nationality_comments' => [
                'label' => __('Comments'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'comments'],
            ],
        ]);
    }

    protected function getSorters(): Collection
    {
        return collect([
            'first_name' => [
                'label' => __('First Name'),
                'sorting' => 'first_name',
            ],
            'last_name' => [
                'label' => __('Last Name'),
                'sorting' => 'family_name',
            ],
            'nationality' => [
                'label' => __('Nationality'),
                'sorting' => 'nationality',
            ],
            'gender' => [
                'label' => __('Gender'),
                'sorting' => 'gender',
            ],
            'age' => [
                'label' => __('Age'),
                'sorting' => 'age',
            ],
            'residence' => [
                'label' => __('Residence'),
                'sorting' => 'residence',
            ],
            'pickup_location' => [
                'label' => __('Pickup location'),
                'sorting' => 'pickup_location',
            ],
        ]);
    }
}
