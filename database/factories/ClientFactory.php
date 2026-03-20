<?php

namespace Database\Factories;

use App\Domain\Client\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Client\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name'   => $this->faker->name(),
            'email'  => $this->faker->unique()->safeEmail(),
            'phone'  => $this->faker->unique()->numerify('+7##########'),
            'phone1' => $this->faker->optional()->numerify('+7##########'),
            'phone2' => $this->faker->optional()->numerify('+7##########'),
        ];
    }
}
