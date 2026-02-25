<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => ucwords($this->faker->words(3, true)),
            'description' => $this->faker->sentence(50),
            'settings' => [
                'hideFromProductList' => false,
                'hideProductFromBreadcrumbs' => false,
                'enableAwaitingConsideration' => false,
                'enableSandboxMode' => false,
                'serviceDeskLink' => '',
                'isArchived' => false,
            ],
        ];
    }

    public function archived(): static
    {
        return $this->state(function (array $attributes) {
            $settings = $attributes['settings'] ?? [];
            $settings['isArchived'] = true;

            return ['settings' => $settings];
        });
    }

    public function existing()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => $this->faker->numberBetween(1, 20),
            ];
        });
    }
}
