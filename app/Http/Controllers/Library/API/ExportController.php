<?php

namespace App\Http\Controllers\Library\API;

use App\Exports\Library\BooksExport;
use App\Exports\Library\BorrowerExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableApiActions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExportController extends Controller
{
    use ExportableApiActions;

    protected function exportAuthorize()
    {
        $this->authorize('operate-library');
        // $this->authorize('viewAny', LibraryBook::class);
        // $this->authorize('viewAny', Person::class);
    }

    protected function exportViewArgs(): array
    {
        return [
            'selections' => [
                'lent_books' => __('library.lent_books'),
                'all_books' => __('library.all_books'),
                'active_borrowers' => __('library.active_borrowers'),
                'overdue_borrowers' => __('library.overdue_borrowers'),
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
                Rule::in(['lent_books', 'all_books', 'active_borrowers', 'overdue_borrowers', 'all_borrowers']),
            ],
        ];
    }

    protected function exportFilename(Request $request): string
    {
        $selections = [
            'lent_books' => __('library.lent_books'),
            'all_books' => __('library.all_books'),
            'active_borrowers' => __('library.active_borrowers'),
            'overdue_borrowers' => __('library.overdue_borrowers'),
            'all_borrowers' => __('library.all_borrowers'),
        ];
        return sprintf('%s - %s (%s)', __('library.library'), $selections[$request->selection], Carbon::now()->toDateString());
    }

    protected function exportExportable(Request $request)
    {
        if (in_array($request->selection, ['lent_books', 'all_books'])) {
            $export = new BooksExport();
            $export->lentOnly = $request->selection == 'lent_books';
            return $export;
        }

        if (in_array($request->selection, ['active_borrowers', 'overdue_borrowers', 'all_borrowers'])) {
            $export = new BorrowerExport();
            $export->activeOnly = $request->selection == 'active_borrowers';
            $export->overdueOnly = $request->selection == 'overdue_borrowers';
            return $export;
        }
    }

    protected function exportDownload(Request $request, $export, $file_name, $file_ext)
    {
        return $export->download($file_name . '.' . $file_ext);
    }
}
