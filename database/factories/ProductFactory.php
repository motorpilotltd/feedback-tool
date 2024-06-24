<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
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
            ],
        ];
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
