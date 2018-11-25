<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Person;
use App\CouponType;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\People\Bank\DownloadFile;
use Dompdf\Dompdf;
use Dompdf\Options;

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

        $persons = Person::orderBy('name', 'asc')
            ->orderBy('family_name', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        $couponTypes = CouponType::orderBy('order')->orderBy('name')->get();

        // Excel export
        if ($request->format == 'xlsx') {
            return self::createExcel($persons, $couponTypes);
        }
        
        // PDF export
        if ($request->format == 'pdf') {
            return self::createPdf($persons, $couponTypes);
        }
    }

    private static function createExcel($persons, $couponTypes) {
        return \Excel::create(__('people.bank') . '_' . Carbon::now()->toDateString(), function($excel) use ($persons, $couponTypes) {
            $excel->sheet(__('people.withdrawals'), function($sheet) use($persons, $couponTypes) {
                $sheet->setOrientation('landscape');
                $sheet->freezeFirstRow();
                $sheet->loadView('bank.export-table',[
                    'persons' => $persons,
                    'couponTypes' => $couponTypes,
                ]);
            });
            $excel->getActiveSheet()->setAutoFilter(
                $excel->getActiveSheet()->calculateWorksheetDimension()
            );
        })->export('xlsx');
    }

    private static function createPdf($persons, $couponTypes) {
        $view = view('bank.export-pdf',[
            'persons' => $persons,
            'couponTypes' => $couponTypes,
        ])->render();

        $options = new Options();
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($view);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        $dompdf->set_option('dpi', 96);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        return $dompdf->stream(__('people.bank') . '_' . Carbon::now()->toDateString());
    }

    /**
     * View for importing persons from file.
     * 
     * @return \Illuminate\Http\Response
     */
    function import() {
		return view('bank.import');
    }

    function doImport(Request $request) {
        $this->validate($request, [
            'file' => 'required|file',
        ]);
        $file = $request->file('file');
        
        \Excel::selectSheets()->load($file, function($reader) {

            $reader->each(function($sheet) {

                // Loop through all rows
                $sheet->each(function($row) {
                    
                    if (!empty($row->name)) {
                        $person = Person::create([
                            'name' => $row->name,
                            'family_name' => isset($row->surname) ? $row->surname : $row->family_name,
                            'police_no' => is_numeric($row->police_no) ? $row->police_no : null,
                            'registration_no' => isset($row->registration_no) ? $row->registration_no : null,
                            'section_card_no' => isset($row->section_card_no) ? $row->section_card_no : null,
                            'nationality' => $row->nationality,
                            'remarks' => $row->remarks,
                        ]);
                        foreach ($row as $k => $v) {
                            if (!empty($v)) {

                            }
                        }
                    }
                });

            });
        });
		return redirect()->route('bank.withdrawal')
				->with('success', _('app.import_successful'));		
    }
  
}
