<?php

use App\Role;
use App\RolePermission;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = factory(Role::class, 15)->create()->each(function ($role) {
            $role->permissions()->saveMany(factory(RolePermission::class, mt_rand(0, 5))->make());
        });
        factory(User::class, 100)->create()->each(function ($user) use ($roles) {
            $user->roles()->attach($roles->random(mt_rand(0, 5))->unique()->values());
        });

        // Assign administrators to roles
        $roles->each(function ($role) {
            $num_users = $role->users->count();
            if ($num_users > 0) {
                $num_admins = mt_rand(0, min(3, $num_users));
                $admins = Arr::random($role->users->pluck('id')->toArray(), $num_admins);
                $role->administrators()->attach($admins);
            }
        });
    }
}
