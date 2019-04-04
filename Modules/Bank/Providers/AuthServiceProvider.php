<?php

namespace Modules\Bank\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

    ];

    protected $permissions = [
        'bank.withdrawals.do' => [
            'label' => 'bank::permissions.do_bank_withdrawals',
            'sensitive' => true,
        ],
        'bank.deposits.do' => [
            'label' => 'bank::permissions.do_bank_deposits',
            'sensitive' => false,
        ],
        'bank.statistics.view' => [
            'label' => 'bank::permissions.view_bank_statistics',
            'sensitive' => false,
        ],
        'bank.configure' => [
            'label' => 'bank::permissions.configure_bank',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-bank-index' => ['bank.withdrawals.do', 'bank.deposits.do', 'bank.configure'],
        'do-bank-withdrawals' => 'bank.withdrawals.do',
        'do-bank-deposits' => 'bank.deposits.do',
        'view-bank-reports' => 'bank.statistics.view',
        'configure-bank' => 'bank.configure',
    ];

}
