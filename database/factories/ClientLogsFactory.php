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
            'section_id' => $this->faker->randomElement([1, 2, 3, 4,5,6,7,8,9,10,11,12,13,14,15]), // Assuming section IDs are integers
            'phone_number' => $this->faker->numerify('09#########'), // Philippine mobile format
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
