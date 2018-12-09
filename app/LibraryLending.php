<?php

namespace App;

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
        return $this->belongsTo('App\LibraryBook', 'book_id');
    }

    public function person()
    {
        return $this->belongsTo('App\Person', 'person_id');
    }
}
