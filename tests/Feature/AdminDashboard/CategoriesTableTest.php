<?php

use App\Livewire\Admin\CategoriesTable;
use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;


beforeEach(function () {
    setupData();
});

it('can display categories', function () {
    login($this->userSuperAdmin)
        ->livewire(CategoriesTable::class)
        ->set('productId', $this->product1->id)
        ->assertSee($this->category1->name)
        ->assertSee($this->category1->user->name)
        ->assertSee($this->category1->ideas_count);
})->group('cat123');

it('can sort categories by name', function () {
    login($this->userSuperAdmin)
        ->livewire(CategoriesTable::class)
        ->set('sortField', 'name')
        ->set('sortDirection', 'asc')
        ->set('productId', $this->product1->id)
        ->assertSee($this->category1->name)
        ->assertSeeInOrder([
            $this->category1->name,
            $this->category2->name,
            $this->category3->name,
        ]);

    login($this->userSuperAdmin)
        ->livewire(CategoriesTable::class)
        ->set('sortField', 'name')
        ->set('sortDirection', 'desc')
        ->set('productId', $this->product1->id)
        ->assertSeeInOrder([
            $this->category3->name,
            $this->category2->name,
            $this->category1->name,
        ]);
})->group('cat123');
