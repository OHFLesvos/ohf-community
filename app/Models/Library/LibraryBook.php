<?php

namespace App\Models\Library;

use App;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Languages;
use Nicebooks\Isbn\Exception\InvalidIsbnException;
use Nicebooks\Isbn\Exception\IsbnNotConvertibleException;
use Nicebooks\Isbn\Isbn;

class LibraryBook extends Model
{
    use NullableFields;

    protected $nullable = [
        'author',
        'isbn',
        'language',
    ];

    public function lendings()
    {
        return $this->hasMany(LibraryLending::class, 'book_id');
    }

    /**
     * Scope a query to only include currently lent.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLent($query)
    {
        return $query->whereHas('lendings', function (Builder $query) {
            $query->active();
        });
    }

    public function activeLending(): ?LibraryLending
    {
        return $this->lendings()->active()->first();
    }

    public function setIsbnAttribute($value)
    {
        if ($value != null) {
            $val = preg_replace('/[^+0-9x]/i', '', $value);
            try {
                $isbn = Isbn::of($val);
                $this->attributes['isbn10'] = preg_replace('/[^+0-9x]/i', '', $isbn->to10()->format());
                $this->attributes['isbn13'] = preg_replace('/[^+0-9x]/i', '', $isbn->to13()->format());
                return;
            } catch (InvalidIsbnException $e) {
                Log::warning('Tried to set invalid ISBN: ' . $e->getMessage());
            } catch (IsbnNotConvertibleException $e) {
                Log::warning('Tried to set not convertible ISBN: ' . $e->getMessage());
            }
        }
        $this->attributes['isbn10'] = null;
        $this->attributes['isbn13'] = null;
    }

    public function getIsbnAttribute()
    {
        if ($this->isbn13 != null) {
            $isbn = Isbn::of($this->isbn13);
            return $isbn->format();
        }
        return null;
    }

    public function setLanguageAttribute($value)
    {
        if ($value !== null) {
            $this->language_code = Languages::lookup(null, App::getLocale())
                ->map(fn ($l) => strtolower($l))
                ->flip()
                ->get(strtolower($value));
        } else {
            $this->language_code = null;
        }
    }

    public function getLanguageAttribute()
    {
        if ($this->language_code !== null) {
            return Languages::lookup([$this->language_code], App::getLocale())->first();
        }
        return null;
    }
}
