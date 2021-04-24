<?php

namespace App\Http\Controllers\Export;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait ExportableActions
{
    abstract protected function exportAuthorize();
    abstract protected function exportView(): string;
    abstract protected function exportViewArgs(): array;
    abstract protected function exportValidateArgs(): array;
    abstract protected function exportFilename(Request $request): string;
    abstract protected function exportExportable(Request $request);

    private static function getFormats()
    {
        return [
            'xlsx' => __('app.excel_xls'),
            'csv' => __('app.comma_separated_values_csv'),
            'tsv' => __('app.tab_separated_values_tsv'),
            'pdf' => __('app.pdf_pdf'),
        ];
    }

    /**
     * Display a form for setting export options.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->exportAuthorize();

        $args = [
            'formats' => self::getFormats(),
            'format' => array_keys(self::getFormats())[0],
        ];
        return view($this->exportView(), array_merge($args, $this->exportViewArgs()));
    }

    /**
     * Prepare and download export as file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function doExport(Request $request)
    {
        $this->exportAuthorize();

        $args = [
            'format' => [
                'required',
                Rule::in(array_keys(self::getFormats())),
            ],
        ];
        $request->validate(array_merge($args, $this->exportValidateArgs()));

        $file_name = $this->exportFilename($request);
        if ($request->format == 'csv') {
            $file_ext = 'csv';
        } elseif ($request->format == 'tsv') {
            $file_ext = 'tsv';
        } elseif ($request->format == 'pdf') {
            $file_ext = 'pdf';
        } else {
            $file_ext = 'xlsx';
        }

        $export = $this->exportExportable($request);
        return $this->exportDownload($request, $export, $file_name, $file_ext);
    }

    protected function exportDownload(Request $request, $export, $file_name, $file_ext)
    {
        return $export->download($file_name . '.' . $file_ext);
    }
}
