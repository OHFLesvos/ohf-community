<?php

namespace Modules\Library\Entities;

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
        return $this->hasMany('Modules\Library\Entities\LibraryLending', 'book_id');
    }

    public function setIsbnAttribute($value) {
        if ($value != null) {
            $val = preg_replace('/[^+0-9x]/i', '', $value);
            $isbn = new Isbn($val);
            if ($isbn->isValid()) {
                $this->attributes['isbn10'] = preg_replace('/[^+0-9x]/i', '', $isbn->format('ISBN-10'));
                $this->attributes['isbn13'] = preg_replace('/[^+0-9x]/i', '', $isbn->format('ISBN-13'));
                return;
            }
        }
        $this->attributes['isbn10'] = null;
        $this->attributes['isbn13'] = null;
    }

    public function getIsbnAttribute() {
        if ($this->isbn13 != null) {
            $isbn = new Isbn($this->isbn13);
            if ($isbn->isValid()) {
                return $isbn->format('ISBN-13');
            }
        }
        return null;
    }

}
