<?php

namespace Modules\Inventory\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Inventory\Entities\InventoryItemTransaction::class => \Modules\Inventory\Policies\ItemTransactionPolicy::class,
        \Modules\Inventory\Entities\InventoryStorage::class         => \Modules\Inventory\Policies\StoragePolicy::class,
    ];

    protected $permissions = [
        'inventory.storage.view' => [
            'label' => 'inventory::permissions.view_inventory_storage',
            'sensitive' => false,
        ],
        'inventory.storage.manage' => [
            'label' => 'inventory::permissions.manage_inventory_storage',
            'sensitive' => false,
        ],
        'inventory.transactions.create' => [
            'label' => 'inventory::permissions.create_inventory_transactions',
            'sensitive' => false,
        ],
        'inventory.transactions.delete' => [
            'label' => 'inventory::permissions.delete_inventory_transactions',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
    ];

}
