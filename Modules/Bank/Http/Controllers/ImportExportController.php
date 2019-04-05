<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;

use Modules\Bank\Exports\BankExport;
use Modules\Bank\Http\Requests\DownloadFile;

use Illuminate\Support\Facades\Config;

use Carbon\Carbon;

class ImportExportController extends Controller
{
    /**
     * View for downloading file of person records.
     * 
     * @return \Illuminate\Http\Response
     */
    function export() {
		return view('bank::export', [
            'formats' => Config::get('bank.export_formats'),
            'selectedFormat' => 'xlsx',
        ]);
    }

    /**
     * Download persons in bank as Excel or PDF file.
     * 
     * @param  \App\Http\Requests\People\Bank\DownloadFile  $request
     * @return \Illuminate\Http\Response
     */
	public function doExport(DownloadFile $request) {
        $this->authorize('export', Person::class);

        $file_name = __('bank::bank.bank') . '_' . Carbon::now()->toDateString();

        $export = new BankExport();

        // Excel export
        if ($request->format == 'xlsx') {
            return $export->download($file_name . '.' . 'xlsx');
        }
        
        // PDF export
        if ($request->format == 'pdf') {
            return $export->download($file_name . '.' . 'pdf');
        }
    }

}
