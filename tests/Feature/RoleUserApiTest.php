<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RoleUserApiTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexWithoutAuthentication()
    {
        $role = Role::factory()->create();

        $response = $this->getJson('api/roles/' . $role->id . '/users', []);

        $this->assertGuest();
        $response->assertUnauthorized();
    }

    public function testIndexWithoutAuthorization()
    {
        $role = Role::factory()->create();

        $authUser = User::factory()->make();

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/' . $role->id . '/users', []);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testIndexWithNonExistingUser()
    {
        Config::set('Debug', false);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/' . 1234 .'/users', []);

        $this->assertAuthenticated();
        $response->assertNotFound()
            ->assertExactJson([
                'message' => 'No query results for model [' . Role::class . '] 1234',
            ]);
    }

    public function testIndexWithoutRecords()
    {
        $role = Role::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/' . $role->id . '/users', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [ ],
            ]);
    }

    public function testIndexWithRecords()
    {
        $role = Role::factory()->create();
        $user1 = User::factory()->create([
            'name' => 'User A',
        ]);
        $user2 = User::factory()->create([
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
                        'avatar_url' => $user1->avatarUrl(),
                        'provider_name' => $user1->provider_name,
                        'created_at' => $user1->created_at->toJSON(),
                        'updated_at' => $user1->updated_at->toJSON(),
                        'links' => [
                            'parent' => route('api.users.index'),
                            'self' => route('api.users.show', $user1),
                            'show' => route('users.show', $user1),
                            'edit' => route('users.edit', $user1),
                        ],
                        'relationships' => [
                            'roles' => [
                                'links' => [
                                    'related' =>route('api.users.roles.index', $user1),
                                    'self' => route('api.users.relationships.roles.index', $user1),
                                ],
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
                        'avatar_url' => $user2->avatarUrl(),
                        'provider_name' => $user2->provider_name,
                        'created_at' => $user2->created_at->toJSON(),
                        'updated_at' => $user2->updated_at->toJSON(),
                        'links' => [
                            'parent' => route('api.users.index'),
                            'self' => route('api.users.show', $user2),
                            'show' => route('users.show', $user2),
                            'edit' => route('users.edit', $user2),
                        ],
                        'relationships' => [
                            'roles' => [
                                'links' => [
                                    'related' =>route('api.users.roles.index', $user2),
                                    'self' => route('api.users.relationships.roles.index', $user2),
                                ],
                            ],
                        ],
                    ]
                ],
            ]);
    }
}
