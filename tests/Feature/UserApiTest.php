<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIndexWithoutAuthentication()
    {
        $response = $this->getJson('api/users', []);

        $this->assertGuest();
        $response->assertUnauthorized();
    }

    public function testIndexWithoutAuthorization()
    {
        $authUser = User::factory()->make();

        $response = $this->actingAs($authUser)
            ->getJson('api/users', []);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testIndexWithoutRecords()
    {
        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [ ],
                'links' => [
                    'first' => route('api.users.index') . '?page=1',
                    'last' => route('api.users.index') . '?page=1',
                    'prev' => null,
                    'next' => null,
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => null,
                    'last_page' => 1,
                    'path' => route('api.users.index'),
                    'per_page' => 10,
                    'to' => null,
                    'total' => 0,
                ],
            ]);
    }

    public function testIndexWithOneRecord()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'locale' => $user->locale,
                        'is_super_admin' => $user->is_super_admin,
                        'is_2fa_enabled' => false,
                        'avatar_url' => $user->avatarUrl(),
                        'provider_name' => $user->provider_name,
                        'created_at' => $user->created_at->toJSON(),
                        'updated_at' => $user->updated_at->toJSON(),
                        'links' => [
                            'parent' => route('api.users.index'),
                            'self' => route('api.users.show', $user),
                            'show' => route('users.show', $user),
                            'edit' => route('users.edit', $user),
                        ],
                        'relationships' => [
                            'roles' => [
                                'links' => [
                                    'related' =>route('api.users.roles.index', $user),
                                    'self' => route('api.users.relationships.roles.index', $user),
                                ],
                            ],
                        ],
                    ],
                ],
                'meta' => [
                    'from' => 1,
                    'to' => 1,
                    'total' => 1,
                ],
            ]);
    }

    public function testIndexWithOrderedRecordsByName()
    {
        $user1 = User::factory()->create([
            'name' => 'User A',
        ]);
        $user2 = User::factory()->create([
            'name' => 'User C',
        ]);
        $user3 = User::factory()->create([
            'name' => 'User B',
        ]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $user1->id,
                        'name' => $user1->name,
                    ],
                    [
                        'id' => $user3->id,
                        'name' => $user3->name,
                    ],
                    [
                        'id' => $user2->id,
                        'name' => $user2->name,
                    ],
                ],
            ]);

        $response2 = $this->actingAs($authUser)
            ->getJson('api/users?sortBy=name&sortDirection=desc', []);

        $response2->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $user2->id,
                        'name' => $user2->name,
                    ],
                    [
                        'id' => $user3->id,
                        'name' => $user3->name,
                    ],
                    [
                        'id' => $user1->id,
                        'name' => $user1->name,
                    ],
                ],
            ]);
    }

    public function testIndexWithFilteredRecords()
    {
        $user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'jonny@example.con'
        ]);
        $user2 = User::factory()->create([
            'name' => 'Paul Smith',
            'email' => 'mister.s@example.com',
        ]);
        $user3 = User::factory()->create([
            'name' => 'Anna Smith',
            'email' => 'miss.s@example.com',
        ]);
        $user4 = User::factory()->create([
            'name' => 'Andrew Brown',
            'email' => 'blacksmith@example.com',
        ]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users?filter=smith', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $user4->id,
                        'name' => $user4->name,
                    ],
                    [
                        'id' => $user3->id,
                        'name' => $user3->name,
                    ],
                    [
                        'id' => $user2->id,
                        'name' => $user2->name,
                    ],
                ],
                'meta' => [
                    'from' => 1,
                    'to' => 3,
                    'total' => 3,
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'id' => $user1->id,
                        'name' => $user1->name,
                    ],
                ],
            ]);
    }

    public function testStoreWithInsufficientPermissions()
    {
        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->postJson('api/users', [
                'name' => $this->faker->name,
                'email' => $this->faker->safeEmail,
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testStoreWithoutRequiredFields()
    {
        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users', [ ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function testStoreWithDuplicateEmail()
    {
        $existingUser = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users', [
                'name' => $existingUser->name,
                'email' => $existingUser->email,
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    public function testStoreWithInvalidEmail()
    {
        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users', [
                'name' => $this->faker->name,
                'email' => $this->faker->name,
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    public function testStoreWithShortPassword()
    {
        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users', [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'password' => Str::random(7),
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password']);
    }

    public function testStoreWithValidInputAndDefaults()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Str::random(40),
        ];

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users', $data);

        $this->assertAuthenticated();
        $response->assertCreated()
            ->assertExactJson([
                'message' => __('app.user_added'),
            ])
            ->assertLocation(route('api.users.show', 1));

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_super_admin' => false,
            'locale' => config('app.locale'),
        ]);

        $this->assertTrue(Hash::check($data['password'], User::find(1)->password));
    }

    public function testStoreWithValidInputAndAllFields()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Str::random(40),
            'locale' => Arr::random(config('language.allowed')),
            'is_super_admin' => true,
        ];

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users', $data);

        $this->assertAuthenticated();
        $response->assertCreated()
            ->assertExactJson([
                'message' => __('app.user_added'),
            ])
            ->assertLocation(route('api.users.show', 1));

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_super_admin' => $data['is_super_admin'],
            'locale' => $data['locale'],
        ]);

        $this->assertTrue(Hash::check($data['password'], User::find(1)->password));
    }

    public function testShowNonExisting()
    {
        Config::set('app.debug', false);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users/123', []);

        $this->assertAuthenticated();
        $response->assertNotFound()
            ->assertExactJson([
                'message' => 'No query results for model [' . User::class . '] 123',
            ]);
    }

    public function testShowExisting()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users/' . $user->id, []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'locale' => $user->locale,
                    'is_super_admin' => $user->is_super_admin,
                    'is_2fa_enabled' => false,
                    'avatar_url' => $user->avatarUrl(),
                    'provider_name' => $user->provider_name,
                    'created_at' => $user->created_at->toJSON(),
                    'updated_at' => $user->updated_at->toJSON(),
                    'links' => [
                        'parent' => route('api.users.index'),
                        'self' => route('api.users.show', $user),
                        'show' => route('users.show', $user),
                        'edit' => route('users.edit', $user),
                    ],
                    'relationships' => [
                        'roles' => [
                            'links' => [
                                'related' =>route('api.users.roles.index', $user),
                                'self' => route('api.users.relationships.roles.index', $user),
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function testUpdateWithInsufficientPermissions()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id, [
                'name' => $this->faker->name,
                'email' => $this->faker->safeEmail,
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testUpdateWithoutRequiredFields()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id, [ ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function testUpdateWithDuplicateEmail()
    {
        $users = User::factory()->count(2)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $users[0]->id, [
                'name' => $users[1]->name,
                'email' => $users[1]->email,
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    public function testUpdateWithNoChanges()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id, [
                'name' => $user->name,
                'email' => $user->email,
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.user_updated'),
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function testUpdateWithValidInputWithoutTouchingPassword()
    {
        $password = Str::random(40);
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
        ];

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id, $data);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.user_updated'),
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check($password, $user->password));
    }

    public function testUpdateWithNewPassword()
    {
        $user = User::factory()->create();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => Str::random(40),
        ];

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id, $data);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.user_updated'),
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check($data['password'], $user->password));
    }

    public function testUpdateWithValidInputWithAllFields()
    {
        $user = User::factory()->create([
            'is_super_admin' => true,
        ]);
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => Str::random(40),
            'locale' => Arr::random(config('language.allowed')),
            'is_super_admin' => false,
        ];

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id, $data);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.user_updated'),
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'locale' => $data['locale'],
            'is_super_admin' => $data['is_super_admin'],
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check($data['password'], $user->password));
    }

    public function testDestroyWithInsufficientPermissions()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/users/' . $user->id, [ ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testDestroyWithCorrectPermissions()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/users/' . $user->id, [ ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.user_deleted'),
            ]);

        $this->assertDeleted('users', [
            'id' => $user->id,
        ]);
    }

    public function testDestroyLastSuperAdmin()
    {
        $user = User::factory()->create([
            'is_super_admin' => true,
        ]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/users/' . $user->id, [ ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testDestroySuperAdminWithRemainingSuperAdmin()
    {
        $users = User::factory()->count(2)->create([
            'is_super_admin' => true,
        ]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/users/' . $users[0]->id, [ ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.user_deleted'),
            ]);

        $this->assertDeleted('users', [
            'id' => $users[0]->id,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $users[1]['name'],
            'email' => $users[1]['email'],
            'is_super_admin' => true,
        ]);
    }
}
