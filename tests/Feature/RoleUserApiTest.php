<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RoleUserApiTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexWithoutAuthentication()
    {
        $role = factory(Role::class)->create();

        $response = $this->getJson('api/roles/' . $role->id . '/users', []);

        $this->assertGuest();
        $response->assertUnauthorized();
    }

    public function testIndexWithoutAuthorization()
    {
        $role = factory(Role::class)->create();

        $authUser = factory(User::class)->make();

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/' . $role->id . '/users', []);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testIndexWithNonExistingUser()
    {
        Config::set('app.debug', false);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/' . 1234 .'/users', []);

        $this->assertAuthenticated();
        $response->assertNotFound()
            ->assertExactJson([
                'message' => 'No query results for model [App\\Role] 1234',
            ]);
    }

    public function testIndexWithoutRecords()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/' . $role->id . '/users', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [ ],
                'links' => [
                    'self' => route('api.users.index'),
                ],
            ]);
    }

    public function testIndexWithRecords()
    {
        $role = factory(Role::class)->create();
        $user1 = factory(User::class)->create([
            'name' => 'User A',
        ]);
        $user2 = factory(User::class)->create([
            'name' => 'User B',
        ]);
        $role->users()->sync([$user1->id, $user2->id]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/' . $role->id . '/users', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    [
                        'id' => $user1->id,
                        'name' => $user1->name,
                        'email' => $user1->email,
                        'locale' => $user1->locale,
                        'is_super_admin' => $user1->is_super_admin,
                        'is_2fa_enabled' => false,
                        'avatar' => $user1->avatar,
                        'provider_name' => $user1->provider_name,
                        'created_at' => $user1->created_at->toJSON(),
                        'updated_at' => $user1->updated_at->toJSON(),
                        'links' => [
                            'parent' => route('api.users.index'),
                            'self' => route('api.users.show', $user1),
                        ],
                        'relationships' => [
                            'roles' => [
                                'links' => [
                                    'related' =>route('api.users.roles.index', $user1),
                                    'self' => route('api.users.relationships.roles.index', $user1),
                                ],
                                'total' => 1,
                            ],
                        ],
                    ],
                    [
                        'id' => $user2->id,
                        'name' => $user2->name,
                        'email' => $user2->email,
                        'locale' => $user2->locale,
                        'is_super_admin' => $user2->is_super_admin,
                        'is_2fa_enabled' => false,
                        'avatar' => $user2->avatar,
                        'provider_name' => $user2->provider_name,
                        'created_at' => $user2->created_at->toJSON(),
                        'updated_at' => $user2->updated_at->toJSON(),
                        'links' => [
                            'parent' => route('api.users.index'),
                            'self' => route('api.users.show', $user2),
                        ],
                        'relationships' => [
                            'roles' => [
                                'links' => [
                                    'related' =>route('api.users.roles.index', $user2),
                                    'self' => route('api.users.relationships.roles.index', $user2),
                                ],
                                'total' => 1,
                            ],
                        ],
                    ]
                ],
                'links' => [
                    'self' => route('api.users.index'),
                ],
            ]);
    }
}
