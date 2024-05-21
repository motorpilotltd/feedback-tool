<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'created_by' => User::factory(),
            'name' => 'Category ' . $this->faker->word(),
            'description' => $this->faker->sentences(2, true)
        ];
    }

    public function existing()
    {
        return $this->state(function (array $attributes) {
            return [
                'product_id' => $this->faker->numberBetween(1, 5),
                'created_by' => $this->faker->numberBetween(1, 20)
            ];
        });
    }
}
