<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FileAttachments;

class FileAttachmentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomDigitNotNull(),
            'item_id' => $this->faker->randomDigitNotNull(),
            'client_file_name' => $this->faker->word(),
            'file_name' => $this->faker->word(),
            'section' => $this->faker->word(),
        ];
    }
}
