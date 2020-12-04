<?php

namespace App\Models\Library;

use App\Models\People\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryLending extends Model
{
    use HasFactory;

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
            ->whereDate('lending_date', '<=', today())
            ->whereNull('returned_date');
    }

    /**
     * Scope a query to only include overdie lendings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->whereDate('return_date', '<', today())
            ->whereNull('returned_date');
    }

    public function book()
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function getIsOverdueAttribute()
    {
        return $this->returned_date === null && $this->return_date->lt(today());
    }
}
