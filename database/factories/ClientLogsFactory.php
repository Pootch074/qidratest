<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClientLogs;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientLogs>
 */
class ClientLogsFactory extends Factory
{
    protected $model = ClientLogs::class;

    public function definition(): array
    {
        return [
            'fullname' => $this->faker->name(),
            'section' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'phone_number' => $this->faker->numerify('09#########'), // Philippine mobile format
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
