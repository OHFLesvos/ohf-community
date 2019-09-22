<?php

namespace Modules\School\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Student extends Pivot
{
    protected $table = 'school_students';

    protected $fillable = [
    ];

}
