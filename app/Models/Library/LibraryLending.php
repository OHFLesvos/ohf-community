<?php

namespace App\Models\Library;

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
        return $this->belongsTo('App\Models\Library\LibraryBook', 'book_id');
    }

    public function person()
    {
        return $this->belongsTo('App\Models\People\Person', 'person_id');
    }
}
