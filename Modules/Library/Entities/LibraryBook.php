<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;

use Iatstuti\Database\Support\NullableFields;

use Nicebooks\Isbn\Isbn;
use Nicebooks\Isbn\InvalidIsbnException;
use Nicebooks\Isbn\IsbnNotConvertibleException;

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
            try {
                $isbn = Isbn::of($val);
                $this->attributes['isbn10'] = preg_replace('/[^+0-9x]/i', '', $isbn->to10()->format());
                $this->attributes['isbn13'] = preg_replace('/[^+0-9x]/i', '', $isbn->to13()->format());
                return;
            } catch (InvalidIsbnException $e) {}
            catch (IsbnNotConvertibleException $e) {}
        }
        $this->attributes['isbn10'] = null;
        $this->attributes['isbn13'] = null;
    }

    public function getIsbnAttribute() {
        if ($this->isbn13 != null) {
            $isbn = Isbn::of($this->isbn13);
            return $isbn->format();
        }
        return null;
    }

}
