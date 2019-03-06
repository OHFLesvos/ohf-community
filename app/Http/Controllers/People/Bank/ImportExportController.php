<?php

namespace App\Http\Controllers\People\Bank;

use App\Person;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\Bank\DownloadFile;
use App\Exports\BankExport;

use Illuminate\Http\Request;

use Carbon\Carbon;

class ImportExportController extends Controller
{
    public static $formats = [
        'xlsx' => 'Excel',
        'pdf' => 'PDF',
    ];

    /**
     * View for downloading file of person records.
     * 
     * @return \Illuminate\Http\Response
     */
    function export() {
		return view('bank.export', [
            'formats' => self::$formats,
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

        $file_name = __('people.bank') . '_' . Carbon::now()->toDateString();

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
