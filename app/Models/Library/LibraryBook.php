<?php

namespace App\Models\Library;

use App\Models\Traits\LanguageCodeField;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Nicebooks\Isbn\Exception\InvalidIsbnException;
use Nicebooks\Isbn\Exception\IsbnNotConvertibleException;
use Nicebooks\Isbn\Isbn;

class LibraryBook extends Model
{
    use HasFactory;
    use NullableFields;
    use LanguageCodeField;

    protected $nullable = [
        'author',
        'isbn',
        'language',
    ];

    protected $fillable = [
        'title',
        'author',
        'language_code',
        'isbn',
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
        return $query->whereHas('lendings', fn (Builder $query) => $query->active());
    }

    /**
     * Scope a query to only include currently available.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->whereDoesntHave('lendings', fn (Builder $query) => $query->active())
            ->orDoesntHave('lendings');
    }

    /**
     * Scope a query to only include records matching the given filter value
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter($query, string $filter)
    {
        $query->where('title', 'LIKE', '%' . $filter . '%')
            ->orWhere('author', 'LIKE', '%' . $filter . '%');
        if (preg_match('/^[0-9x-]+$/i', $filter)) {
            $query->orWhere('isbn10', 'LIKE', preg_replace('/[^+0-9x]/i', '', $filter) . '%');
            $query->orWhere('isbn13', 'LIKE', preg_replace('/[^+0-9x]/i', '', $filter) . '%');
        }
        return $query;
    }

    public function getLabelAttribute(): string
    {
        $label = $this->title;
        if (! empty($this->author)) {
            $label .= ' (' . $this->author . ')';
        }
        if (! empty($this->isbn)) {
            $label .= ', ' . $this->isbn;
        }
        return $label;
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
}
