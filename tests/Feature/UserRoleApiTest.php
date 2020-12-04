<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class UserRoleApiTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexWithoutAuthentication()
    {
        $user = User::factory()->create();

        $response = $this->getJson('api/users/' . $user->id . '/roles', []);

        $this->assertGuest();
        $response->assertUnauthorized();
    }

    public function testIndexWithoutAuthorization()
    {
        $user = User::factory()->create();

        $authUser = User::factory()->make();

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
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users/' . $user->id . '/roles', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [ ],
            ]);
    }

    public function testIndexWithRecords()
    {
        $user = User::factory()->create();
        $role1 = Role::factory()->create([
            'name' => 'Role A',
        ]);
        $role2 = Role::factory()->create([
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
                            'show' => route('roles.show', $role1),
                            'edit' => route('roles.edit', $role1),
                        ],
                        'relationships' => [
                            'administrators' => [
                                'links' => [
                                    'related' => route('api.roles.administrators.index', $role1),
                                    'self' => route('api.roles.relationships.administrators.index', $role1),
                                ],
                            ],
                            'users' => [
                                'links' => [
                                    'related' =>route('api.roles.users.index', $role1),
                                    'self' => route('api.roles.relationships.users.index', $role1),
                                ],
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
                            'show' => route('roles.show', $role2),
                            'edit' => route('roles.edit', $role2),
                        ],
                        'relationships' => [
                            'administrators' => [
                                'links' => [
                                    'related' => route('api.roles.administrators.index', $role2),
                                    'self' => route('api.roles.relationships.administrators.index', $role2),
                                ],
                            ],
                            'users' => [
                                'links' => [
                                    'related' =>route('api.roles.users.index', $role2),
                                    'self' => route('api.roles.relationships.users.index', $role2),
                                ],
                            ],
                        ],
                    ]
                ],
            ]);
    }

}
