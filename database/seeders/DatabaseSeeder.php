<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Product;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Assign app admin a super-admin role
        $user = User::where('email', config('const.ADMIN_EMAIL'))->get()->first();
        if (! $user) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => config('const.ADMIN_EMAIL'),
            ]);
        }

        User::factory(19)->create();
        Product::factory(5)->existing()->create();
        Category::factory(10)->existing()->create();

        $this->call([
            StatusesSeeder::class,
        ]);

        Idea::factory(25)->existing()->create();

        // Generate unique votes. Ensure idea_id and user_id are unique for each row
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

        // Generate comments for Ideas
        foreach (Idea::all() as $idea) {
            $numComments = 8;
            if ($idea->id % 2 === 0) {
                $numComments = 28;
            }
            Comment::factory($numComments)->existing()->create(['idea_id' => $idea->id]);
        }

        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

    }
}
