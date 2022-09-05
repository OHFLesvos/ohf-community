<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
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
        /** @var User $authUser */
        $authUser = User::factory()->make();

        $response = $this->actingAs($authUser)
            ->getJson('api/roles', []);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testIndexWithoutRecords()
    {
        /** @var User $authUser */
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
        $role = Role::factory()->create();

        /** @var User $authUser */
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
                        'can_update' => false,
                        'can_delete' => false,
                        'created_at' => $role->created_at,
                        'updated_at' => $role->updated_at,
                        'links' => [
                            'parent' => route('api.roles.index'),
                            'self' => route('api.roles.show', $role),
                            'show' => route('roles.show', $role),
                            'edit' => route('roles.edit', $role),
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
        $role1 = Role::factory()->create([
            'name' => 'Role A',
        ]);
        $role2 = Role::factory()->create([
            'name' => 'Role C',
        ]);
        $role3 = Role::factory()->create([
            'name' => 'Role B',
        ]);

        /** @var User $authUser */
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
        $role1 = Role::factory()->create([
            'name' => 'Accountant',
        ]);
        $role2 = Role::factory()->create([
            'name' => 'Security Officer',
        ]);
        $role3 = Role::factory()->create([
            'name' => 'Finance Officer',
        ]);
        $role4 = Role::factory()->create([
            'name' => 'Security Guard',
        ]);

        /** @var User $authUser */
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
        /** @var User $authUser */
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
        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles', [ ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testStoreWithDuplicateName()
    {
        $existingRole = Role::factory()->create([
            'name' => $this->faker->jobTitle,
        ]);

        /** @var User $authUser */
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

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles', $data);

        $this->assertAuthenticated();
        $response->assertCreated()
            ->assertExactJson([
                'message' => __('Role has been added.'),
            ])
            ->assertLocation(route('api.roles.show', 1));

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);
    }

    public function testShowNonExisting()
    {
        Config::set('Debug', false);

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/123', []);

        $this->assertAuthenticated();
        $response->assertNotFound()
            ->assertExactJson([
                'message' => 'No query results for model [' . Role::class . '] 123',
            ]);
    }

    public function testShowExisting()
    {
        $role = Role::factory()->create();

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/' . $role->id, []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'can_update' => false,
                    'can_delete' => false,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                    'links' => [
                        'parent' => route('api.roles.index'),
                        'self' => route('api.roles.show', $role),
                        'show' => route('roles.show', $role),
                        'edit' => route('roles.edit', $role),
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
        $role = Role::factory()->create();

        /** @var User $authUser */
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
        $role = Role::factory()->create();

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/' . $role->id, [ ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testUpdateWithDuplicateName()
    {
        $roles = Role::factory()->count(2)->create();

        /** @var User $authUser */
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
        $role = Role::factory()->create();

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/' . $role->id, [
                'name' => $role->name,
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('Role has been updated.'),
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => $role->name,
        ]);
    }

    public function testUpdateWithValidInput()
    {
        $role = Role::factory()->create();
        $data = [
            'name' => $this->faker->jobTitle,
        ];

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/' . $role->id, $data);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('Role has been updated.'),
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);
    }

    public function testDestroyWithInsufficientPermissions()
    {
        $role = Role::factory()->create();

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/roles/' . $role->id, [ ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testDestroyWithCorrectPermissions()
    {
        $role = Role::factory()->create();

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/roles/' . $role->id, [ ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('Role has been deleted.'),
            ]);

        $this->assertModelMissing($role);
    }
}
