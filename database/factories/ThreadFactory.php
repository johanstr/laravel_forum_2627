<?php

namespace Database\Factories;

use App\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Thread>
 */
class ThreadFactory extends Factory
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
            'created_at' => fake()->dateTimeBetween('-40 days', '-30 days'),
            'updated_at' => fn (array $attributes) => $attributes['created_at'],
        ];
    }
}
