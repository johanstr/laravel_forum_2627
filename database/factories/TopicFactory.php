<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Topic>
 */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'user_id' => fake()->numberBetween(1, 10),
            'content' => fake()->paragraph(),
            // Threads 19 en 20 blijven leeg
            'thread_id' => fake()->numberBetween(1, 18),
            'created_at' => fake()->dateTimeBetween('-30 days', '-7 days'),
            'updated_at' => fn (array $attributes) => $attributes['created_at'],
        ];
    }
}
