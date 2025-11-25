<?php

namespace Database\Factories;

use App\Domain\Rbac\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word,
            'description' => fake()->text(128),
            'guard_name' => 'sanctum',
        ];
    }
}
