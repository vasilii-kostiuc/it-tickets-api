<?php

namespace Database\Factories;

use App\Domain\Rbac\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        $entity = $this->faker->randomAscii();
        $action = $this->faker->randomElement(['view', 'create', 'edit', 'delete']);

        return [
            'name' => "{$entity}.{$action}",
            'display_name' => "{$action} {$entity}",
            'guard_name' => 'sanctum'
        ];
    }
}
