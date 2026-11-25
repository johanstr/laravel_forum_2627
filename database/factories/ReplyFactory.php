<?php

namespace Database\Factories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reply>
 */
class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph(),
            'user_id' => fake()->numberBetween(1, 10),
            // Topics 46 t/m 50 blijven onbeantwoord
            'topic_id' => fake()->numberBetween(1, 45),
            'created_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'updated_at' => fn (array $attributes) => $attributes['created_at'],
        ];
    }
}
