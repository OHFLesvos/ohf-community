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

    public function book()
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
