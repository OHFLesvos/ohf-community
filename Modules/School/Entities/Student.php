<?php

namespace Modules\School\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Student extends Pivot
{
    protected $table = 'school_students';

    protected $fillable = [
        'remarks',
    ];

    // public function person() {
    //     return $this->belongsTo(\Modules\People\Entities\Person::class);
    // }

    // public function class() {
    //     return $this->belongsTo(SchoolClass::class);
    // }
}
