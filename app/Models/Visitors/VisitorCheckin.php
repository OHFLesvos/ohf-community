<?php

namespace App\Models\Visitors;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorCheckin extends Model
{
    use HasFactory;
    use NullableFields;

    protected $fillable = [
        'purpose_of_visit',
    ];

    protected $nullable = [
        'purpose_of_visit',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
