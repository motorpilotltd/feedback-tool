<?php

use App\Livewire\Product\Index as ProductIndex;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Product;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->product1 = Product::factory()->create([
        'name' => 'AAA Product Name 1 TestSearch',
        'created_at' => now()->addMinute(),
        'user_id' => User::factory()->create(['name' => 'AA User Name']),
    ]);
    $c1 = Category::factory()->create(['product_id' => $this->product1]);
    Idea::factory()->create([
        'title' => 'Nullam luctus mi ac',
        'category_id' => $c1,

    ]);

    $this->product2 = Product::factory()->create([
        'name' => 'ZZZ Product Name 2 TestSearch',
        'created_at' => now(),
        'user_id' => User::factory()->create(['name' => 'ZZ User Name']),
    ]);

    $this->perPage = $this->product1->getPerPage();
});

it('can show product index livewire', function () {
    login()->get(route('product.index'))
        ->assertSee(__('product.select_a_product'))
        ->assertSeeLivewire('product.index');
});

it('can show no items found when there are no product', function () {
    Product::query()->delete();
    login()->get(route('product.index'))
        ->assertDontSeeLivewire('product.container')
        ->assertSee(__('general.noitemsfound', ['items' => 'products']));
});

it('can show created product in the product lists', function () {
    $new = Product::factory()->create(['name' => 'Test Product', 'created_at' => now()->addMinute()]);
    livewire(ProductIndex::class)
        ->call('sortBy', 'created_at')
        ->call('sortBy', 'created_at')
        ->assertViewHas('products', function ($products) use ($new) {
            return in_array($new->name, $products->pluck('name')->toArray());
        });
});

it('can show product count per page', function () {
    Product::factory($this->perPage - 1)->create(); // Fill in the whole page

    login()->get(route('product.index'))
        ->assertSee($this->product1->name)
        ->assertDontSee($this->product2->name);

    livewire(ProductIndex::class)
        ->assertViewHas('products', function ($products) {
            return $products->count() === $this->perPage;
        });
});

it('can show products pagination works', function () {
    Product::factory($this->perPage - 1)->create(); // Fill in the whole page

    $p2 = Product::latest('name')->first();
    // Page 1
    login()->get(route('product.index'))
        ->assertDontSee($p2->name)
        ->assertSee($this->product1->name);
    // Page 2
    $this->get(route('product.index', ['page' => 2]))
        ->assertSee($p2->name)
        ->assertDontSee($this->product1->name);
});

it('can show search product correct result count', function () {
    Product::factory()->create([
        'name' => 'Foobar',
    ]);
    $searchFor = 'TestSearch';
    livewire(ProductIndex::class)
        ->set('search', $searchFor)
        ->assertViewHas('products', function ($products) {
            return $products->count() === 2;
        });
});

it('can show search product matched idea', function () {
    Product::factory()->create([
        'name' => 'Foobar',
    ]);
    $searchFor = 'TestSearch';
    livewire(ProductIndex::class)
        ->set('search', $searchFor)
        ->assertViewHas('products', function ($products) {
            return in_array($this->product1->name, $products->pluck('name')->toArray());
        });
});

it('can show no result when search didn\'t match any product name', function () {
    $searchFor = 'asdfasdfadf';
    livewire(ProductIndex::class)
        ->set('search', $searchFor)
        ->assertViewHas('products', function ($products) {
            return $products->count() === 0;
        });
});

it('can sort products with sortBy function', function () {
    login()->livewire(ProductIndex::class)
        ->call('sortBy', 'name')
        ->call('sortBy', 'name')
        ->assertViewHas('sortField', function ($field) {
            return $field == 'name';
        })
        ->assertViewHas('sortDirection', function ($direction) {
            return $direction == 'asc';
        })
        ->assertViewHas('products', function ($products) {
            return $products->first()->name === $this->product1->name;
        });
});

it('can sort products by', function ($data) {
    [$sortField, $sortDirection, $productName] = $data;
    login()->livewire(ProductIndex::class)
        ->set('sortField', $sortField)
        ->set('sortDirection', $sortDirection)
        ->assertViewHas('products', function ($products) use ($productName) {
            return $products->first()->name === $productName;
        });
})->with([
    'name in ascending order' => fn () => ['name', 'asc', $this->product1->name],
    'name in descending order' => fn () => ['name', 'desc', $this->product2->name],
    'created_at in ascending order' => fn () => ['created_at', 'asc', $this->product2->name],
    'created_at in descending order' => fn () => ['created_at', 'desc', $this->product1->name],
    'category count in ascending order' => fn () => ['categories_count', 'asc', $this->product2->name],
    'category count in descending order' => fn () => ['categories_count', 'desc', $this->product1->name],
    'idea count in ascending order' => fn () => ['ideas_count', 'asc', $this->product2->name],
    'idea count in descending order' => fn () => ['ideas_count', 'desc', $this->product1->name],
    'user\'s name in ascending order' => fn () => ['ideas_count', 'asc', $this->product2->name],
    'user\'s name in  descending order' => fn () => ['ideas_count', 'desc', $this->product1->name],
]);
