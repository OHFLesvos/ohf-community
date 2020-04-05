<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class UserRoleRelationshipApiTest extends TestCase
{
    use RefreshDatabase;

    //
    // Index
    //

    public function testIndexWithoutAuthentication()
    {
        $user = factory(User::class)->create();

        $response = $this->getJson('api/users/' . $user->id . '/relationships/roles', []);

        $this->assertGuest();
        $response->assertUnauthorized();
    }

    public function testIndexWithoutAuthorization()
    {
        $user = factory(User::class)->create();

        $authUser = factory(User::class)->make();

        $response = $this->actingAs($authUser)
            ->getJson('api/users/' . $user->id . '/relationships/roles', []);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testIndexWithNonExistingUser()
    {
        Config::set('app.debug', false);

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->getJson('api/users/' . 1234 . '/relationships/roles', []);

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
            ->getJson('api/users/' . $user->id . '/relationships/roles', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => [ ],
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
            ->getJson('api/users/' . $user->id . '/relationships/roles', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => [
                        $role1->id,
                        $role2->id,
                    ],
                ],
            ]);
    }

    //
    // Store
    //

    public function testStoreWithInsufficientPermissions()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->postJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testStoreWithNonExistingUser()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users/' . 1234 . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertNotFound();
    }

    public function testStoreWithNonExistingRole()
    {
        $user = factory(User::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    1234
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['id.0']);
    }

    public function testStoreWithValidRole()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertCreated()
            ->assertExactJson([
                'message' => __('app.role_added'),
            ]);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    public function testStoreWithAlreadyAttachedRole()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $user->roles()->attach($role);

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->postJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertCreated()
            ->assertExactJson([
                'message' => __('app.role_added'),
            ]);

        // TODO find a way to check that only one single record exists
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    //
    // Update
    //

    public function testUpdateWithInsufficientPermissions()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testUpdateWithNonExistingUser()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . 1234 . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertNotFound();
    }

    public function testUpdateWithNonExistingRole()
    {
        $role1 = factory(Role::class)->create();
        $user = factory(User::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    $role1->id,
                    1234
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['id.1']);
    }

    public function testUpdateWithValidRoles()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();
        $role3 = factory(Role::class)->create();
        $user->roles()->attach([$role1->id, $role2->id]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    $role2->id,
                    $role3->id,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.role_updated'),
            ]);

        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $role1->id,
        ]);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role2->id,
        ]);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role3->id,
        ]);
    }

    public function testUpdateWithEmptyRoles()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();
        $user->roles()->attach([$role1->id, $role2->id]);

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->putJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [ ],
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.role_updated'),
            ]);

        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $role1->id,
        ]);
        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $role2->id,
        ]);
    }

    //
    // Destroy
    //

    public function testDestroyWithInsufficientPermissions()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.view');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertForbidden();
    }

    public function testDestroyWithNonExistingUser()
    {
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/users/' . 1234 . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertNotFound();
    }

    public function testDestroyWithNonExistingRole()
    {
        $user = factory(User::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    1234,
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['id.0']);
    }

    public function testDestroyWithoutAttachedRole()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.role_removed'),
            ]);

        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    public function testDestroyWitAttachedRole()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $user->roles()->attach($role);

        $authUser = $this->makeUserWithPermission('app.usermgmt.users.manage');

        $response = $this->actingAs($authUser)
            ->deleteJson('api/users/' . $user->id . '/relationships/roles', [
                'id' => [
                    $role->id
                ],
            ]);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertExactJson([
                'message' => __('app.role_removed'),
            ]);

        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

}
