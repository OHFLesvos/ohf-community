<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use Biblys\Isbn\Isbn as Isbn;

class LibraryBook extends Model
{
    use NullableFields;

    protected $nullable = [
        'author',
        'isbn',
        'language',
    ];

    public function lendings() {
        return $this->hasMany('App\LibraryLending', 'book_id');
    }

    public function getIsbn10Attribute() {
        if ($this->isbn != null) {
            $isbn = new Isbn($this->isbn);
            if ($isbn->isValid()) {
                return $isbn->format('ISBN-10');
            }
        }
        return null;
    }

    public function getIsbn13Attribute() {
        if ($this->isbn != null) {
            $isbn = new Isbn($this->isbn);
            if ($isbn->isValid()) {
                return $isbn->format('ISBN-13');
            }
        }
        return null;
    }

}
