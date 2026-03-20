<?php

namespace Database\Factories;

use App\Domain\Department\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Department\Models\Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'       => $this->faker->unique()->company(),
            'ext_mask'   => $this->faker->numerify('1##'),
            'queue1'     => $this->faker->numerify('queue-###'),
            'queue2'     => $this->faker->numerify('queue-###'),
            'manager_id' => \App\Domain\User\Models\User::factory(),
            'sla_id'     => null,
        ];
    }
}
