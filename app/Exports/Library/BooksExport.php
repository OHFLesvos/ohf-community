<?php

namespace App\Exports\Library;

use App\Exports\BaseExport;
use App\Models\Library\LibraryBook;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BooksExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    public bool $lentOnly = false;

    public function __construct()
    {
        $this->orientation = 'landscape';
    }

    public function query()
    {
        $query = LibraryBook::query();
        if ($this->lentOnly) {
            $query->lent();
        }
        return $query->orderBy('title', 'asc');
    }

    public function title(): string
    {
        return $this->lentOnly ? __('library.lent_books') : __('library.books');
    }

    public function headings(): array
    {
        $headings = [
            __('library.book'),
            __('library.author'),
            __('library.isbn'),
            __('app.language'),
            __('app.registered'),
        ];
        if ($this->lentOnly) {
            $headings[] = __('library.lent_until');
            $headings[] = __('library.overdue');
        } else {
            $headings[] = '# ' . __('library.lendings');
        }
        return $headings;
    }

    /**
     * @param LibraryBook $book
     */
    public function map($book): array
    {
        $mapping = [
            $book->title,
            $book->author,
            $book->isbn,
            $book->language,
            $book->created_at->toDateString(),
        ];
        if ($this->lentOnly) {
            $activeLending = $book->activeLending();
            $mapping[] = $activeLending->return_date->toDateString();
            $mapping[] = $activeLending->is_overdue ? __('app.yes') : __('app.no');
        } else {
            $mapping[] = (int) $book->lendings()->count();
        }
        return $mapping;
    }

}
