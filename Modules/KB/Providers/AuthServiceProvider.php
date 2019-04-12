<?php

namespace Modules\KB\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\KB\Entities\WikiArticle::class => \Modules\KB\Policies\ArticlePolicy::class,
    ];

    protected $permissions = [
        'wiki.view' => [
            'label' => 'kb::permissions.view_wiki',
            'sensitive' => false,
        ],
        'wiki.edit' => [
            'label' => 'kb::permissions.edit_wiki',
            'sensitive' => false,
        ],
        'wiki.delete' => [
            'label' => 'kb::permissions.delete_wiki',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [

    ];

}
