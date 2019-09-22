<?php

namespace Modules\School\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [       
        \Modules\School\Entities\SchoolClass::class => \Modules\School\Policies\SchoolClassPolicy::class,
        \Modules\School\Entities\Student::class     => \Modules\School\Policies\StudentPolicy::class,
    ];

    protected $permissions = [
        'school.classes.view' => [
            'label' => 'school::permissions.view_classes',
            'sensitive' => false,
        ],
        'school.classes.manage' => [
            'label' => 'school::permissions.manage_classes',
            'sensitive' => false,
        ],
        'school.students.view' => [
            'label' => 'school::permissions.view_students',
            'sensitive' => true,
        ],
        'school.students.manage' => [
            'label' => 'school::permissions.manage_students',
            'sensitive' => true,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-school' => 'school.classes.view',
    ];

}
