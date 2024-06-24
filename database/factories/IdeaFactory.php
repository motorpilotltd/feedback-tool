<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdeaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Idea::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'added_by' => User::factory(),
            'author_id' => User::factory(),
            'title' => ucfirst($this->faker->words(5, true)),
            'content' => $this->faker->paragraphs(4, true),
            'created_at' => now()->addSeconds(rand(2, 59)),
        ];
    }

    public function existing()
    {

        return $this->state(function (array $attributes) {
            $statusids = Status::all()->pluck('id')->toArray();
            $rand = array_rand($statusids, 1);

            return [
                'category_id' => $this->faker->numberBetween(1, 10),
                'added_by' => $this->faker->numberBetween(1, 20),
                'author_id' => $this->faker->numberBetween(1, 20),
                'status' => Status::find($statusids[$rand])->slug,
            ];
        });
    }
}
