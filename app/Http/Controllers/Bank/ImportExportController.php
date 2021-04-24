<?php

namespace App\Http\Controllers\Bank;

use App\Exports\Bank\BankExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\DownloadFile;
use App\Models\People\Person;
use Carbon\Carbon;

class ImportExportController extends Controller
{
    /**
     * View for downloading file of person records.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        return view('bank.export', [
            'formats' => collect(config('bank.export_formats'))
                ->mapWithKeys(fn ($val, $key) => [ $key => __($val)]),
            'selectedFormat' => 'xlsx',
        ]);
    }

    /**
     * Download persons in bank as Excel or PDF file.
     *
     * @param  \App\Http\Requests\People\Bank\DownloadFile  $request
     * @return \Illuminate\Http\Response
     */
    public function doExport(DownloadFile $request)
    {
        $this->authorize('export', Person::class);

        $file_name = __('bank.bank') . '_' . Carbon::now()->toDateString();

        $export = new BankExport();

        // Excel export
        if ($request->format == 'xlsx') {
            $extension = 'xlsx';
            return $export->download($file_name . '.' . $extension);
        }

        // PDF export
        if ($request->format == 'pdf') {
            $extension = 'pdf';
            return $export->download($file_name . '.' . $extension);
        }
    }
}
