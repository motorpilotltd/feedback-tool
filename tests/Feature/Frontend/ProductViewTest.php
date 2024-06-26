<?php

use App\Livewire\Idea\IdeaCardsContainer;
use App\Livewire\SideBar\CategoryLinks;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Support\Str;

it('can show product details', function () {
    $p1 = Product::factory()->create([
        'name' => 'A product Name 1',
        'description' => 'Product name 1 description text',
    ]);

    login()->get(route('product.show', ['product' => $p1]))
        ->assertOk()
        ->assertSee($p1->name)
        ->assertSee($p1->description);
});

it('can show ideas belong to the current product', function () {
    $p1 = Product::factory()->create([
        'name' => 'A product Name 1',
        'description' => 'Product name 1 description text',
    ]);
    $p2 = Product::factory()->create(['name' => 'Product 2']);

    $c1 = Category::factory()->create(['product_id' => $p1]);
    $c2 = Category::factory()->create(['product_id' => $p2]);

    $status = Status::factory()->create([
        'name' => 'Awaiting Consideration',
        'slug' => Str::slug('Awaiting Consideration', ''),
    ]);

    $i1 = Idea::factory()->create([
        'category_id' => $c1,
        'status' => $status->slug,
    ]);

    $i2 = Idea::factory()->create([
        'category_id' => $c2,
        'status' => $status->slug,
    ]);

    login()->get(route('product.show', ['product' => $p1]))
        ->assertOk()
        ->assertSeeLivewire('idea.idea-cards-container');

    login()->livewire(IdeaCardsContainer::class, ['product' => $p1])
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 1;
        })
        ->assertSee($i1->title)
        ->assertDontSee($i2->title);
});

it('can show product\'s ideas pagination', function () {
    $p1 = Product::factory()->create(['name' => 'A product name']);
    $c1 = Category::factory()->create(['product_id' => $p1]);

    $status = Status::factory()->create([
        'name' => 'Awaiting Consideration',
        'slug' => Str::slug('Awaiting Consideration', ''),
    ]);

    $ideaOldest = Idea::factory()->create([
        'title' => 'Idea Test Last',
        'category_id' => $c1,
        'status' => $status->slug,
        'created_at' => now()->addSeconds(1),
    ]);

    Idea::factory($ideaOldest->getPerPage() + 5)->create(['category_id' => $c1, 'status' => $status->slug]);

    $ideaLatest = Idea::latest()->first();
    // Page 1
    login()->get(route('product.show', [
        'product' => $p1,
        'page' => 1,
    ]))
        ->assertSee($ideaLatest->title)
        ->assertDontSee($ideaOldest->title);
    // Page 2
    login()->get(route('product.show', [
        'product' => $p1,
        'page' => 2,
    ]))
        ->assertDontSee($ideaLatest->title)
        ->assertSee($ideaOldest->title);
});

it('can show product\'s category links in the sidebar', function () {
    $p1 = Product::factory()->create(['name' => 'Product 1']);
    $p2 = Product::factory()->create(['name' => 'Product 2']);

    $c1 = Category::factory()->create(['product_id' => $p1]);
    $c2 = Category::factory()->create(['product_id' => $p2]);

    $status = Status::factory()->create([
        'name' => 'Awaiting Consideration',
        'slug' => Str::slug('Awaiting Consideration', ''),
    ]);

    $i1 = Idea::factory()->create([
        'category_id' => $c1,
        'status' => $status->slug,
    ]);

    $i2 = Idea::factory()->create([
        'category_id' => $c2,
        'status' => $status->slug,
    ]);

    login()->get(route('product.show', ['product' => $p1]))
        ->assertSeeLivewire('side-bar.category-links');

    login()->livewire(CategoryLinks::class, ['productId' => $p1->id])
        ->assertViewHas('categories', function ($categories) {
            return $categories->count() === 2;
        })
        ->assertSee($c1->name)
        ->assertDontSee($c2->name);
});
