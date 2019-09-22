<?php

namespace Modules\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'teacher_name',
        'capacity',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function students() {
        return $this->belongsToMany(Student::class, 'school_students');
    }

}
