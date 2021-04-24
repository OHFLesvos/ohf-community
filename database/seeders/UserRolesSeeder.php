<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
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
        $roles = Role::factory()
            ->count(15)
            ->create();
        User::factory()
            ->count(100)
            ->create()
            ->each(function (User $user) use ($roles) {
                $user->roles()
                    ->attach(
                        $roles->random(mt_rand(0, 5))
                        ->unique()
                        ->values()
                    );
            });

        // Assign administrators to roles
        $roles->each(function (Role $role) {
            $num_users = $role->users->count();
            if ($num_users > 0) {
                $num_admins = mt_rand(0, min(3, $num_users));
                $admins = Arr::random($role->users->pluck('id')->toArray(), $num_admins);
                $role->administrators()->attach($admins);
            }
        });
    }
}
