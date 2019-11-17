<?php

namespace Modules\Helpers\Http\Controllers;

use App\Http\Controllers\Export\ExportableActions;

use Modules\Helpers\Entities\Helper;
use Modules\Helpers\Http\Requests\ImportHelpers;
use Modules\Helpers\Exports\HelpersExport;
use Modules\Helpers\Imports\HelpersImport;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

use Carbon\Carbon;

use ZipStream\ZipStream;

use JeroenDesloovere\VCard\VCard;

class HelperExportImportController extends BaseHelperController
{
    use ExportableActions;

    protected function exportAuthorize()
    {
        $this->authorize('export', Helper::class);
    }

    protected function exportView(): string
    {
        return 'helpers::export';
    }

    protected function exportViewArgs(): array
    {
        return [
            'scopes' => $this->getScopes()->mapWithKeys(function($s, $k){
                return [ $k => $s['label'] ];
            })->toArray(),
            'scope' => $this->getScopes()->keys()->first(),
            'columnt_sets' => $this->getColumnSets()->mapWithKeys(function($s, $k){
                return [ $k => $s['label'] ];
            })->toArray(),
            'columnt_set' => $this->getColumnSets()->keys()->first(),
            'sorters' => $this->getSorters()->mapWithKeys(function($s, $k){
                return [ $k => $s['label'] ];
            })->toArray(),
            'sorting' => $this->getSorters()->keys()->first(),
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
        return __('helpers::helpers.helpers') .'_' . $scope['label'] .'_' . Carbon::now()->toDateString();
    }

    protected function exportExportable(Request $request)
    {
        $columnSet = $this->getColumnSets()[$request->column_set];
        $fields = self::filterFieldsByColumnSet($this->getFields(), $columnSet);
        $scope = $this->getScopes()[$request->scope];
        $sorting = $this->getSorters()[$request->sorting];       

        $export = new HelpersExport($fields, $scope['scope']);
        $export->setOrientation($request->orientation);
        $export->setSorting($sorting['sorting']);
        if ($request->has('fit_to_page')) {
            $export->setFitToWidth(1);
            $export->setFitToHeight(1);
        }
        return $export;
    }

    private static function filterFieldsByColumnSet(array $fields, array $columnSet) {
        return collect($fields)
            ->where('overview_only', false)
            ->where('exclude_export', false)
            ->filter(function($e){ 
                return !isset($e['authorized_view']) || $e['authorized_view']; 
            })
            ->filter(function($e) use($columnSet){
                if (count($columnSet['columns']) > 0) {
                    if (isset($e['form_name'])) {
                        return in_array($e['form_name'], $columnSet['columns']);
                    }
                    return false;
                }
                return true;
            });
    }

    protected function exportDownload(Request $request, $export, $file_name, $file_ext) {
        // Download as ZIP with portraits
        if ($request->has('include_portraits')) {            
            $zip = new ZipStream($file_name . '.zip');
            $temp_file = 'temp/' . uniqid() . '.' . $file_ext;
            $export->store($temp_file);
            $zip->addFileFromPath($file_name . '.' . $file_ext, storage_path('app/' . $temp_file));
            Storage::delete($temp_file);
            $scopeMethod = $scope = $this->getScopes()[$request->scope]['scope'];
            $helpers = Helper::{$scopeMethod}()
                ->get()
                ->load('person');
            foreach ($helpers as $helper) {
                if (isset($helper->person->portrait_picture)) {
                    $picture_path = storage_path('app/'.$helper->person->portrait_picture);
                    if (is_file($picture_path)) {
                        $ext = pathinfo($picture_path, PATHINFO_EXTENSION);
                        $zip->addFileFromPath('portraits/' . $helper->person->fullName . '.' . $ext , $picture_path);
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

    function import() {
        $this->authorize('import', Helper::class);

        return view('helpers::import');
    }

    function doImport(ImportHelpers $request) {
        $this->authorize('import', Helper::class);

        $fields = self::getImportFields($this->getFields());

        $importer = new HelpersImport($fields);
        $importer->import($request->file('file'));

		return redirect()->route('people.helpers.index')
				->with('success', __('app.import_successful'));		
    }

    private static function getImportFields($fields) {
        return collect($fields)
            ->where('overview_only', false)
            ->filter(function($f){ return isset($f['assign']) && is_callable($f['assign']); })
            ->map(function($f){
                return [
                    'labels' => self::getAllTranslations($f['label_key'])
                        ->concat(isset($f['import_labels']) && is_array($f['import_labels']) ? $f['import_labels'] : [])
                        ->map(function($l){ return strtolower($l); }),
                    'assign' => $f['assign'],
                ];
            });
    }
    
    /**
     * Download vcard
     * 
     * @param  \Modules\Helpers\Entities\Helper  $helper
     * @return \Illuminate\Http\Response
     */
    function vcard(Helper $helper)
    {
        $this->authorize('view', $helper);

        // define vcard
        $vcard = new VCard();
        // if ($helper->company != null) {
        //     $vcard->addCompany($helper->company);
        // }
        $vcard->addCompany(Config::get('app.name'));

        if ($helper->person->family_name != null || $helper->person->name != null) {
            $vcard->addName($helper->person->family_name, $helper->person->name, '', '', '');
        }
        if ($helper->email != null) {
            $vcard->addEmail($helper->email);
        }
        if ($helper->local_phone != null) {
            $vcard->addPhoneNumber(preg_replace('/[^+0-9]/', '', $helper->local_phone), 'HOME');
        }
        if ($helper->whatsapp != null && $helper->local_phone != $helper->whatsapp) {
            $vcard->addPhoneNumber(preg_replace('/[^+0-9]/', '', $helper->whatsapp), 'WORK');
        }

        if (isset($helper->person->portrait_picture)) {
            $contents = Storage::get($helper->person->portrait_picture);
            if ($contents != null) {
                $vcard->addPhotoContent($contents);
            }
        }

        // return vcard as a download
        return $vcard->download();
    }
}
