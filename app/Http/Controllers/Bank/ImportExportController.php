<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;

use App\Models\People\Person;

use App\Exports\Bank\BankExport;
use App\Http\Requests\Bank\DownloadFile;

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
		return view('bank.export', [
            'formats' => collect(Config::get('bank.export_formats'))
                ->mapWithKeys(function($v, $k){
                    return [ $k => __($v)];
                }),
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

        $file_name = __('bank.bank') . '_' . Carbon::now()->toDateString();

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
