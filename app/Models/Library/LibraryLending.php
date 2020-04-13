<?php

namespace App\Models\Library;

use App\Models\People\Person;
use Illuminate\Database\Eloquent\Model;

class LibraryLending extends Model
{
    protected $dates = [
        'lending_date',
        'return_date',
        'returned_date',
    ];

    /**
     * Scope a query to only include active lendings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('lending_date')
            ->whereNotNull('return_date')
            ->whereDate('lending_date', '<=', today())
            ->whereDate('return_date', '>=', today());
    }

    public function book()
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
