<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Product;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed sample/demo data for local development.
     *
     * This relies on model factories, which depend on fakerphp/faker — a
     * dev-only Composer dependency that is not installed on deployed
     * environments. DatabaseSeeder therefore only calls this seeder when
     * running in the local environment.
     */
    public function run(): void
    {
        User::factory(19)->create();
        Product::factory(5)->existing()->create();
        Category::factory(10)->existing()->create();

        Idea::factory(25)->existing()->create();

        // Generate unique votes. Ensure idea_id and user_id are unique for each row.
        foreach (range(1, 20) as $user_id) {
            foreach (range(1, 25) as $idea_id) {
                if ($idea_id % 2 === 0) {
                    Vote::factory()->create([
                        'user_id' => $user_id,
                        'idea_id' => $idea_id,
                    ]);
                }
            }
        }

        // Generate comments for ideas.
        foreach (Idea::all() as $idea) {
            $numComments = $idea->id % 2 === 0 ? 28 : 8;
            Comment::factory($numComments)->existing()->create(['idea_id' => $idea->id]);
        }
    }
}
