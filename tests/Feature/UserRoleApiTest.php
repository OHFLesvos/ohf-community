<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class UserRoleApiTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexWithoutAuthentication()
    {
        $user = factory(User::class)->create();

        $response = $this->getJson('api/users/' . $user->id . '/roles', []);

        $this->assertGuest();
        $response->assertUnauthorized();
    }

    public function testIndexWithoutAuthorization()
    {
        $user = factory(User::class)->create();

        $authUser = factory(User::class)->make();

        $response = $this->actingAs($authUser)
            ->getJson('api/users/' . $user->id . '/roles', []);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testIndexWithNonExistingUser()
    {
        Config::set('app.debug', false);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users/' . 1234 . '/roles', []);

        $this->assertAuthenticated();
        $response->assertNotFound()
            ->assertExactJson([
                'message' => 'No query results for model [App\\User] 1234',
            ]);
    }

    public function testIndexWithoutRecords()
    {
        $user = factory(User::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users/' . $user->id . '/roles', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [ ],
                'links' => [
                    'self' => route('api.roles.index'),
                ],
                'meta' => [
                    'total' => 0,
                ],
            ]);
    }

    public function testIndexWithRecords()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create([
            'name' => 'Role A',
        ]);
        $role2 = factory(Role::class)->create([
            'name' => 'Role B',
        ]);
        $user->roles()->sync([$role1->id, $role2->id]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users/' . $user->id . '/roles', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    [
                        'id' => $role1->id,
                        'name' => $role1->name,
                        'created_at' => $role1->created_at,
                        'updated_at' => $role1->updated_at,
                        'links' => [
                            'parent' => route('api.roles.index'),
                            'self' => route('api.roles.show', $role1),
                        ],
                        'relationships' => [
                            'administrators' => [
                                'links' => [
                                    'related' => route('api.roles.administrators.index', $role1),
                                    'self' => route('api.roles.relationships.administrators.index', $role1),
                                ],
                                'total' => 0,
                            ],
                            'users' => [
                                'links' => [
                                    'related' =>route('api.roles.users.index', $role1),
                                    'self' => route('api.roles.relationships.users.index', $role1),
                                ],
                                'total' => 1,
                            ],
                        ],
                    ],
                    [
                        'id' => $role2->id,
                        'name' => $role2->name,
                        'created_at' => $role2->created_at,
                        'updated_at' => $role2->updated_at,
                        'links' => [
                            'parent' => route('api.roles.index'),
                            'self' => route('api.roles.show', $role2),
                        ],
                        'relationships' => [
                            'administrators' => [
                                'links' => [
                                    'related' => route('api.roles.administrators.index', $role2),
                                    'self' => route('api.roles.relationships.administrators.index', $role2),
                                ],
                                'total' => 0,
                            ],
                            'users' => [
                                'links' => [
                                    'related' =>route('api.roles.users.index', $role2),
                                    'self' => route('api.roles.relationships.users.index', $role2),
                                ],
                                'total' => 1,
                            ],
                        ],
                    ]
                ],
                'links' => [
                    'self' => route('api.roles.index'),
                ],
                'meta' => [
                    'total' => 2,
                ],
            ]);
    }

}
