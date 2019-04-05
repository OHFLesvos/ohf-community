<?php

namespace Modules\Library\Entities;

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
        return $this->belongsTo('Modules\Library\Entities\LibraryBook', 'book_id');
    }

    public function person()
    {
        return $this->belongsTo('Modules\People\Entities\Person', 'person_id');
    }
}
