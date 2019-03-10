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

    protected $permission_gate_mappings = [
        'view-accounting-summary' => 'accounting.summary.view',
    ];

}
