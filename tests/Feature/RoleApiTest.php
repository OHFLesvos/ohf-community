<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RoleApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIndexWithoutAuthentication()
    {
        $response = $this->getJson('api/roles', []);

        $this->assertGuest();
        $response->assertUnauthorized();
    }

    public function testIndexWithoutAuthorization()
    {
        $authUser = factory(User::class)->make();

        $response = $this->actingAs($authUser)
            ->getJson('api/roles', []);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testIndexWithoutRecords()
    {
        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [ ],
            ]);
    }

    public function testIndexWithOneRecord()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    [
                        'id' => $role->id,
                        'name' => $role->name,
                        'created_at' => $role->created_at,
                        'updated_at' => $role->updated_at,
                        'links' => [
                            'parent' => route('api.roles.index'),
                            'self' => route('api.roles.show', $role),
                        ],
                        'relationships' => [
                            'administrators' => [
                                'links' => [
                                    'related' => route('api.roles.administrators.index', $role),
                                    'self' => route('api.roles.relationships.administrators.index', $role),
                                ],
                            ],
                            'users' => [
                                'links' => [
                                    'related' =>route('api.roles.users.index', $role),
                                    'self' => route('api.roles.relationships.users.index', $role),
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function testIndexWithOrderedRecordsByName()
    {
        $role1 = factory(Role::class)->create([
            'name' => 'Role A',
        ]);
        $role2 = factory(Role::class)->create([
            'name' => 'Role C',
        ]);
        $role3 = factory(Role::class)->create([
            'name' => 'Role B',
        ]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $role1->id,
                        'name' => $role1->name,
                    ],
                    [
                        'id' => $role3->id,
                        'name' => $role3->name,
                    ],
                    [
                        'id' => $role2->id,
                        'name' => $role2->name,
                    ],
                ],
            ]);

        $response2 = $this->actingAs($authUser)
            ->getJson('api/roles?sortBy=name&sortDirection=desc', []);

        $response2->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $role2->id,
                        'name' => $role2->name,
                    ],
                    [
                        'id' => $role3->id,
                        'name' => $role3->name,
                    ],
                    [
                        'id' => $role1->id,
                        'name' => $role1->name,
                    ],
                ],
            ]);
    }

    public function testIndexWithFilteredRecords()
    {
        $role1 = factory(Role::class)->create([
            'name' => 'Accountant',
        ]);
        $role2 = factory(Role::class)->create([
            'name' => 'Security Officer',
        ]);
        $role3 = factory(Role::class)->create([
            'name' => 'Finance Officer',
        ]);
        $role4 = factory(Role::class)->create([
            'name' => 'Security Guard',
        ]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles?filter=officer', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $role3->id,
                        'name' => $role3->name,
                    ],
                    [
                        'id' => $role2->id,
                        'name' => $role2->name,
                    ],
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'id' => $role1->id,
                        'name' => $role1->name,
                    ],
                    [
                        'id' => $role4->id,
                        'name' => $role4->name,
                    ],
                ],
            ]);
    }

    public function testStoreWithInsufficientPermissions()
    {
        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles', [
                'name' => $this->faker->jobTitle,
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testStoreWithoutRequiredFields()
    {
        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles', [ ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testStoreWithDuplicateName()
    {
        $existingRole = factory(Role::class)->create([
            'name' => $this->faker->jobTitle,
        ]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles', [
                'name' => $existingRole->name,
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testStoreWithValidInput()
    {
        $data = [
            'name' => $this->faker->jobTitle,
        ];

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles', $data);

        $this->assertAuthenticated();
        $response->assertCreated()
            ->assertExactJson([
                'message' => __('app.role_added'),
            ])
            ->assertLocation(route('api.roles.show', 1));

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);
    }

    public function testShowNonExisting()
    {
        Config::set('app.debug', false);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/123', []);

        $this->assertAuthenticated();
        $response->assertNotFound()
            ->assertExactJson([
                'message' => 'No query results for model [App\\Role] 123',
            ]);
    }

    public function testShowExisting()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/' . $role->id, []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                    'links' => [
                        'parent' => route('api.roles.index'),
                        'self' => route('api.roles.show', $role),
                    ],
                    'relationships' => [
                        'administrators' => [
                            'links' => [
                                'related' => route('api.roles.administrators.index', $role),
                                'self' => route('api.roles.relationships.administrators.index', $role),
                            ],
                        ],
                        'users' => [
                            'links' => [
                                'related' =>route('api.roles.users.index', $role),
                                'self' => route('api.roles.relationships.users.index', $role),
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function testUpdateWithInsufficientPermissions()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/' . $role->id, [
                'name' => $this->faker->jobTitle,
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testUpdateWithoutRequiredFields()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/' . $role->id, [ ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testUpdateWithDuplicateName()
    {
        $roles = factory(Role::class, 2)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/' . $roles[0]->id, [
                'name' => $roles[1]->name,
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testUpdateWithNoChanges()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/' . $role->id, [
                'name' => $role->name,
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.role_updated'),
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => $role->name,
        ]);
    }

    public function testUpdateWithValidInput()
    {
        $role = factory(Role::class)->create();
        $data = [
            'name' => $this->faker->jobTitle,
        ];

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/' . $role->id, $data);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.role_updated'),
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);
    }

    public function testDestroyWithInsufficientPermissions()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/roles/' . $role->id, [ ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testDestroyWithCorrectPermissions()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/roles/' . $role->id, [ ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.role_deleted'),
            ]);

        $this->assertDeleted('roles', [
            'id' => $role->id,
        ]);
    }
}
