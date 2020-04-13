<?php

namespace App\Http\Controllers\Library;

use App\Exports\Library\BooksExport;
use App\Exports\Library\BorrowerExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExportController extends Controller
{
    use ExportableActions;

    protected function exportAuthorize()
    {
        $this->authorize('operate-library');
        // $this->authorize('list', LibraryBook::class);
        // $this->authorize('list', Person::class);
    }

    protected function exportView(): string
    {
        return 'library.export';
    }

    protected function exportViewArgs(): array
    {
        return [
            'selections' => [
                'lent_books' => __('library.lent_books'),
                'all_books' => __('library.all_books'),
                'active_borrowers' => __('library.active_borrowers'),
                'all_borrowers' => __('library.all_borrowers'),
            ],
            'selection' => 'lent_books',
        ];
    }

    protected function exportValidateArgs(): array
    {
        return [
            'selection' => [
                'required',
                Rule::in(['lent_books', 'all_books', 'active_borrowers', 'all_borrowers']),
            ],
        ];
    }

    protected function exportFilename(Request $request): string
    {
        if (in_array($request->selection, ['lent_books', 'all_books'])) {
            return __('library.library') . '_' . __('library.books') . '_' . Carbon::now()->toDateString();
        }
        if (in_array($request->selection, ['active_borrowers', 'all_borrowers'])) {
            return __('library.library') . '_' . __('library.borrowers') . '_' . Carbon::now()->toDateString();
        }
    }

    protected function exportExportable(Request $request)
    {
        if (in_array($request->selection, ['lent_books', 'all_books'])) {
            $export = new BooksExport();
            $export->lentOnly = $request->selection == 'lent_books';
            return $export;
        }

        if (in_array($request->selection, ['active_borrowers', 'all_borrowers'])) {
            $export = new BorrowerExport();
            $export->activeOnly = $request->selection == 'active';
            return $export;
        }
    }

    protected function exportDownload(Request $request, $export, $file_name, $file_ext)
    {
        return $export->download($file_name . '.' . $file_ext);
    }
}
