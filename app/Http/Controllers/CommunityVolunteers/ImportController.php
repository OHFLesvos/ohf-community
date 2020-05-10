<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Http\Requests\CommunityVolunteers\ImportCommunityVolunteers;
use App\Imports\CommunityVolunteers\CommunityVolunteersImport;
use App\Models\CommunityVolunteers\CommunityVolunteer;

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
}
