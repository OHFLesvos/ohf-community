<?php

namespace Modules\Wiki\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Wiki\Entities\WikiArticle::class => \Modules\Wiki\Policies\ArticlePolicy::class,
    ];

    protected $permissions = [
        'wiki.view' => [
            'label' => 'wiki::permissions.view_wiki',
            'sensitive' => false,
        ],
        'wiki.edit' => [
            'label' => 'wiki::permissions.edit_wiki',
            'sensitive' => false,
        ],
        'wiki.delete' => [
            'label' => 'wiki::permissions.delete_wiki',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [

    ];

}
