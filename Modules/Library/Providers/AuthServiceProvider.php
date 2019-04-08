<?php

namespace Modules\Library\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Library\Entities\LibraryBook::class => \Modules\Library\Policies\LibraryBookPolicy::class,
        \Modules\Library\Entities\LibraryLending::class => \Modules\Library\Policies\LibraryLendingPolicy::class,
    ];

    protected $permissions = [
        'library.operate' => [
            'label' => 'library::permissions.operate_library',
            'sensitive' => true,
        ],
        'library.configure' => [
            'label' => 'library::permissions.configure_library',
            'sensitive' => true,
        ],  
    ];

    protected $permission_gate_mappings = [
        'operate-library' => 'library.operate',
        'configure-library' => 'library.configure',
    ];

}
