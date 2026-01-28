<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CommentSpam;

class CommentSpamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment_id' => $this->faker->randomDigitNotNull(),
            'user_id' => $this->faker->randomDigitNotNull(),
        ];
    }
}
