<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RoleUserRelationshipApiTest extends TestCase
{
    use RefreshDatabase;

    //
    // Index
    //

    public function testIndexWithoutAuthentication()
    {
        $role = Role::factory()->create();

        $response = $this->getJson('api/roles/'.$role->id.'/relationships/users', []);

        $this->assertGuest();
        $response->assertUnauthorized();
    }

    public function testIndexWithoutAuthorization()
    {
        $role = Role::factory()->create();

        $authUser = User::factory()->make();

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/'.$role->id.'/relationships/users', []);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testIndexWithNonExistingUser()
    {
        Config::set('Debug', false);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/'. 1234 .'/relationships/users', []);

        $this->assertAuthenticated();
        $response->assertNotFound()
            ->assertExactJson([
                'message' => 'No query results for model ['.Role::class.'] 1234',
            ]);
    }

    public function testIndexWithoutRecords()
    {
        $role = Role::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/roles/'.$role->id.'/relationships/users', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => [],
                ],
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
            ->getJson('api/roles/'.$role->id.'/relationships/users', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => [
                        $user1->id,
                        $user2->id,
                    ],
                ],
            ]);
    }

    //
    // Store
    //

    public function testStoreWithInsufficientPermissions()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testStoreWithNonExistingRole()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles/'. 1234 .'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertNotFound();
    }

    public function testStoreWithNonExistingUser()
    {
        $role = Role::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    1234,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['id.0']);
    }

    public function testStoreWithValidUser()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertCreated()
            ->assertExactJson([
                'message' => __('User has been added.'),
            ]);

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);
    }

    public function testStoreWithAlreadyAttachedUser()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();
        $role->users()->attach($user);

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertCreated()
            ->assertExactJson([
                'message' => __('User has been added.'),
            ]);

        // TODO find a way to check that only one single record exists
        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);
    }

    //
    // Update
    //

    public function testUpdateWithInsufficientPermissions()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testUpdateWithNonExistingRole()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/'. 1234 .'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertNotFound();
    }

    public function testUpdateWithNonExistingUser()
    {
        $user1 = User::factory()->create();
        $role = Role::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    $user1->id,
                    1234,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['id.1']);
    }

    public function testUpdateWithValidUsers()
    {
        $role = Role::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $role->users()->attach([$user1->id, $user2->id]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    $user2->id,
                    $user3->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('User has been updated.'),
            ]);

        $this->assertDatabaseMissing('role_user', [
            'role_id' => $role->id,
            'user_id' => $user1->id,
        ]);
        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user2->id,
        ]);
        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user3->id,
        ]);
    }

    public function testUpdateWithEmptyUsers()
    {
        $role = Role::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $role->users()->attach([$user1->id, $user2->id]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [],
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('User has been updated.'),
            ]);

        $this->assertDatabaseMissing('role_user', [
            'role_id' => $role->id,
            'user_id' => $user1->id,
        ]);
        $this->assertDatabaseMissing('role_user', [
            'role_id' => $role->id,
            'user_id' => $user2->id,
        ]);
    }

    //
    // Destroy
    //

    public function testDestroyWithInsufficientPermissions()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testDestroyWithNonExistingRole()
    {
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/roles/'. 1234 .'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertNotFound();
    }

    public function testDestroyWithNonExistingUser()
    {
        $role = Role::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    1234,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['id.0']);
    }

    public function testDestroyWithoutAttachedUser()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('User has been removed.'),
            ]);
    }

    public function testDestroyWitAttachedRole()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();
        $role->users()->attach($user);

        $authUser = $this->makeUserWithPermission('app.usermgmt.roles.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/roles/'.$role->id.'/relationships/users', [
                'id' => [
                    $user->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('User has been removed.'),
            ]);

        $this->assertDatabaseMissing('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);
    }
}
