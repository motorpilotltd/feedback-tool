<?php

use App\Livewire\Admin\ProductsTable;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

use function Pest\Faker\fake;

beforeEach(function () {
    setupData();
});

it('allows super admin to visit Products Table page', function () {
    login($this->userSuperAdmin);
    $this->get(route('admin.products'))
        ->assertStatus(200);
});

it('does not allow product admin to visit Products Table page', function () {
    login($this->userProductAdmin1);
    $this->get(route('admin.products'))
        ->assertStatus(403);
});

it('can display products table', function () {
    login($this->userSuperAdmin);
    $this->get(route('admin.products'))
        ->assertSeeLivewire('admin.products-table');
});

it('can lists products in the table', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->assertViewHas('products', function ($products) {
            return $products->count() > 0;
        })
        ->assertSee($this->product1->name)
        ->assertSee($this->product2->name);
});

it('can search for products by title and return results', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->set('search', $this->product1->name)
        ->assertSee($this->product1->name)
        ->assertDontSee($this->product2->name)
        ->assertViewHas('products', function ($products) {
            return $products->count() === 1;
        });
});

it('doesn\'t return search result when no match found', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->set('search', fake()->md5())
        ->assertDontSee($this->product1->name)
        ->assertDontSee($this->product2->name)
        ->assertViewHas('products', function ($products) {
            return $products->count() === 0;
        });
});

it('opens the product form modal when New button clicked', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->assertDontSee(__('text.addnewproduct'))
        ->call('openCreateModal')
        ->assertSee(__('text.addnewproduct'));
});

it('saves a new product', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->call('openCreateModal')
        ->set('editing.name', fake()->text(10))
        ->set('editing.description', fake()->text(20))
        ->call('save')
        ->assertDispatched('product-created');

});

it('saves the new product\'s logo', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->set('editing.name', fake()->text(10))
        ->set('editing.description', fake()->text(20))
        ->set('newLogo', UploadedFile::fake()->image('new_logo.png'))
        ->call('save')
        ->assertDispatched('filepreview:App\Models\Product:'.Product::latest('id')->first()->id);

    // Get the latest product from the database
    $product = Product::latest('id')->first();

    // Retrieve the media attachments associated with the product
    $attachments = $product->getMedia('attachments');

    // Ensure that there is at least one attachment
    $this->assertNotEmpty($attachments);

    // Get the URL of the first attachment (logo)
    $logoUrl = $attachments->first()->getUrl();

    // Assert that the logo URL is not empty
    $this->assertNotEmpty($logoUrl);
});

it('can sort products by ID', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->set('sortField', 'id')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->product1->id,
            $this->product2->id,
            $this->product3->id,
        ])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->product3->id,
            $this->product2->id,
            $this->product1->id,
        ]);
});

it('can sort products by Name', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->set('sortField', 'name')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->product1->name,
            $this->product2->name,
            $this->product3->name,
        ])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->product3->name,
            $this->product2->name,
            $this->product1->name,
        ]);
});

it('can sort products by Categories Count', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->set('sortField', 'categories_count')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->product1->categories_count,
            $this->product2->categories_count,
            $this->product3->categories_count,
        ])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->product3->categories_count,
            $this->product2->categories_count,
            $this->product1->categories_count,
        ]);
});

it('can sort products by Ideas Count', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->set('sortField', 'ideas_count')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->product1->ideas_count,
            $this->product2->ideas_count,
            $this->product3->ideas_count,
        ])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->product3->ideas_count,
            $this->product2->ideas_count,
            $this->product1->ideas_count,
        ]);
});

it('can sort products by User Name', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->set('sortField', 'users.name')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->product1->user->name,
            $this->product2->user->name,
            $this->product3->user->name,
        ])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->product3->user->name,
            $this->product2->user->name,
            $this->product1->user->name,
        ]);
});

it('can sort products by Created At Date', function () {
    login($this->userSuperAdmin)
        ->livewire(ProductsTable::class)
        ->set('sortField', 'created_at')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->product1->created_at->toDayDateTimeString(),
            $this->product2->created_at->toDayDateTimeString(),
            $this->product3->created_at->toDayDateTimeString(),
        ])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->product3->created_at->toDayDateTimeString(),
            $this->product2->created_at->toDayDateTimeString(),
            $this->product1->created_at->toDayDateTimeString(),
        ]);
});
