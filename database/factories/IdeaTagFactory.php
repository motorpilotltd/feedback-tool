<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IdeaTag;

class IdeaTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tag_id' => $this->faker->randomDigitNotNull(),
            'idea_id' => $this->faker->randomDigitNotNull(),
        ];
    }
}
