<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->jobTitle,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Role $role) {
            $keys = array_keys(config('permissions.keys'));
            $selected_keys = Arr::random($keys, mt_rand(0, min(10, count($keys))));
            $permissions = collect($selected_keys)
                ->map(fn ($key) => (new RolePermission())->withKey($key));
            $role->permissions()->saveMany($permissions);
        });
    }
}
