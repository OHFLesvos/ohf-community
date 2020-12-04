<?php

namespace Tests;

use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
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

        $user = User::factory()->make([
            'id' => 99999,
            'locale' => config('app.locale'),
        ]);
        $role = Role::factory()->make();
        $user->roles[0] = $role;
        $role->permissions[0] = RolePermission::make([
            'key' => $permission,
        ]);

        return $user;
    }
}
