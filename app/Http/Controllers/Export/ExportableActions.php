<?php

namespace App\Http\Controllers\Export;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait ExportableActions
{
    abstract protected function exportAuthorize(): void;

    abstract protected function exportViewArgs(): array;

    abstract protected function exportValidateArgs(): array;

    abstract protected function exportFilename(Request $request): string;

    abstract protected function exportExportable(Request $request);

    /**
     * @return array<string,string>
     */
    private static function getFormats(): array
    {
        return [
            'xlsx' => __('Excel (.xlsx)'),
            'csv' => __('Comma-separated values (.csv)'),
            'tsv' => __('Tab-separated values (.tsv)'),
            'pdf' => __('PDF (.pdf)'),
        ];
    }

    public function export(): JsonResponse
    {
        $this->exportAuthorize();

        $args = [
            'formats' => self::getFormats(),
            'format' => array_keys(self::getFormats())[0],
        ];

        return response()
            ->json(array_merge($args, $this->exportViewArgs()));
    }

    /**
     * Prepare and download export as file.
     */
    public function doExport(Request $request): BinaryFileResponse
    {
        $this->exportAuthorize();

        $args = [
            'format' => [
                'required',
                Rule::in(array_keys(self::getFormats())),
            ],
        ];
        $request->validate(array_merge($args, $this->exportValidateArgs()));

        $export = $this->exportExportable($request);
        $file_name = $this->exportFilename($request);
        $file_ext = match ($request->format) {
            'csv' => 'csv',
            'tsv' => 'tsv',
            'pdf' => 'pdf',
            default => 'xlsx',
        };

        return $this->exportDownload($export, $file_name, $file_ext);
    }

    protected function exportDownload($export, string $file_name, string $file_ext): BinaryFileResponse
    {
        return $export->download($file_name.'.'.$file_ext);
    }
}
