<?php

namespace Modules\Accounting\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Modules\Accounting\Entities\MoneyTransaction::class => Modules\Accounting\Policies\MoneyTransactionPolicy::class,
    ];

    protected $permissions = [
        'accounting.transactions.view' => [
            'label' => 'accounting::permissions.view_transactions',
            'sensitive' => true,
        ],
        'accounting.transactions.create' => [
            'label' => 'accounting::permissions.create_transactions',
            'sensitive' => true,
        ],
        'accounting.transactions.update_delete' => [
            'label' => 'accounting::permissions.update_delete_transactions',
            'sensitive' => true,
        ],
        'accounting.summary.view' => [
            'label' => 'accounting::permissions.view_summary',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-accounting-summary' => 'accounting.summary.view',
    ];

}
