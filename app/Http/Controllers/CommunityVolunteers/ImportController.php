<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Http\Requests\CommunityVolunteers\ImportCommunityVolunteers;
use App\Imports\CommunityVolunteers\CommunityVolunteersImport;
use App\Imports\CommunityVolunteers\HeadingRowImport;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Http\Request;
use App\Models\ImportFieldMapping;

class ImportController extends BaseController
{
    public function import()
    {
        $this->authorize('import', CommunityVolunteer::class);

        return view('cmtyvol.import');
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
            ]));

            $fields = collect($request->map)->filter(fn ($m) => $m['to'] != null)
                ->map(fn ($m) => [
                    'labels' => collect([ strtolower($m['from']) ]),
                    'assign' => $fields->firstWhere('key', $m['to'])['assign'],
                ]);
        }

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
                'key' => $f['label_key'],
                'labels' => self::getAllTranslations($f['label_key'])
                    ->concat(isset($f['import_labels']) && is_array($f['import_labels']) ? $f['import_labels'] : [])
                    ->map(fn ($l) => strtolower($l)),
                'assign' => $f['assign'],
            ]);
    }

    public function getHeaderMappings(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $table_headers = collect((new HeadingRowImport)->toArray($request->file('file'))[0][0]);

        $fields = self::getImportFields($this->getFields());

        $variations = $fields->mapWithKeys(fn ($f) =>
            $f['labels']->mapWithKeys(fn ($l) => [ $l => $f['key'] ])
        );

        $cached_mappings = ImportFieldMapping::model('cmtyvol')
            ->whereIn('from', $table_headers)
            ->get();

        $defaults = $table_headers->mapWithKeys(fn ($f) => [
            $f => $cached_mappings->contains('from', $f)
                ? $cached_mappings->firstWhere('from', $f)['to']
                : $variations->get(strtolower($f)),
        ]);

        $available = collect([ null => '-- ' . __('app.dont_import') . ' --' ])
            ->merge($fields->mapWithKeys(fn ($f) => [ $f['key'] => __($f['key']) ]));

        return [ 'headers' => $table_headers, 'available' => $available, 'defaults' => $defaults ];
    }
}
