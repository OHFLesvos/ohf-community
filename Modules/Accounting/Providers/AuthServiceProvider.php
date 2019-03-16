<?php

namespace Modules\Accounting\Providers;

use App\Providers\BaseAuthServiceProvider;

use Modules\Accounting\Entities\MoneyTransaction;
use Modules\Accounting\Policies\MoneyTransactionPolicy;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        MoneyTransaction::class => MoneyTransactionPolicy::class,
    ];

    protected $permissions = [
        'accounting.transactions.view' => [
            'label' => 'accounting::accounting.view_transactions',
            'sensitive' => true,
        ],
        'accounting.transactions.create' => [
            'label' => 'accounting::accounting.create_transactions',
            'sensitive' => true,
        ],
        'accounting.transactions.update_delete' => [
            'label' => 'accounting::accounting.update_delete_transactions',
            'sensitive' => true,
        ],
        'accounting.summary.view' => [
            'label' => 'accounting::accounting.view_summary',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-accounting-summary' => 'accounting.summary.view',
    ];

}
