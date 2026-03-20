<?php

namespace Database\Factories;

use App\Domain\Ticket\Models\Sla;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Ticket\Models\Sla>
 */
class SlaFactory extends Factory
{
    protected $model = Sla::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => $this->faker->unique()->words(3, true),
            'duration'       => $this->faker->numberBetween(60, 1440),
            'grace_duration' => $this->faker->numberBetween(0, 60),
            'schedule_id'    => null,
            'description'    => $this->faker->optional()->sentence(),
        ];
    }
}
