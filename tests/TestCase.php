<?php

namespace Tests;

use App\Role;
use App\RolePermission;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function makeUserWithPermission($permission)
    {
        // $user = $this->createMock(User::class);
        // $user->expects($this->any())
        //     ->method('hasPermission')
        //     ->with('fundraising.donations.accept_webhooks')
        //     ->willReturn(true);

        $user = factory(User::class)->make([
            'id' => 99999,
            'locale' => config('app.locale'),
        ]);
        $role = factory(Role::class)->make();
        $user->roles[0] = $role;
        $role->permissions[0] = RolePermission::make([
            'key' => $permission,
        ]);

        return $user;
    }
}
