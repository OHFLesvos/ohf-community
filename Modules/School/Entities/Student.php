<?php

namespace Modules\School\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Student extends Pivot
{
    protected $table = 'school_students';

    protected $fillable = [
    ];

    // public function classes() {
    //     return $this->belongsToMany(SchoolClass::class, 'school_students');
    // }

    // public function person() {
    //     return $this->belongsTo(\Modules\People\Entities\Person::class);
    // }
}
