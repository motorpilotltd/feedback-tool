<?php

use App\Livewire\Admin\CategoriesTable;

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
});

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
});

it('can sort categories by ideas count', function () {
    login($this->userSuperAdmin)
        ->livewire(CategoriesTable::class)
        ->set('sortField', 'ideas_count')
        ->set('sortDirection', 'asc')
        ->set('productId', $this->product1->id)
        ->assertSeeInOrder([
            $this->category1->ideas->count(),
            $this->category2->ideas->count(),
            $this->category3->ideas->count(),
        ]);

    login($this->userSuperAdmin)
        ->livewire(CategoriesTable::class)
        ->set('sortField', 'ideas_count')
        ->set('sortDirection', 'desc')
        ->set('productId', $this->product1->id)
        ->assertSeeInOrder([
            $this->category3->ideas->count(),
            $this->category2->ideas->count(),
            $this->category1->ideas->count(),
        ]);
});

it('can sort categories by user name', function () {
    login($this->userSuperAdmin)
        ->livewire(CategoriesTable::class)
        ->set('sortField', 'users.name')
        ->set('sortDirection', 'asc')
        ->set('productId', $this->product1->id)
        ->assertSeeInOrder([
            $this->category1->user->name,
            $this->category2->user->name,
            $this->category3->user->name,
        ]);

    login($this->userSuperAdmin)
        ->livewire(CategoriesTable::class)
        ->set('sortField', 'users.name')
        ->set('sortDirection', 'desc')
        ->set('productId', $this->product1->id)
        ->assertSeeInOrder([
            $this->category3->user->name,
            $this->category2->user->name,
            $this->category1->user->name,
        ]);
});

it('can sort categories by Created At Date', function () {
    login($this->userSuperAdmin)
        ->livewire(CategoriesTable::class)
        ->set('sortField', 'created_at')
        ->set('sortDirection', 'asc')
        ->set('productId', $this->product1->id)
        ->assertSeeInOrder([
            $this->category1->created_at->toDayDateTimeString(),
            $this->category2->created_at->toDayDateTimeString(),
            $this->category3->created_at->toDayDateTimeString(),
        ])
        ->set('sortDirection', 'desc')
        ->set('productId', $this->product1->id)
        ->assertSeeInOrder([
            $this->category3->created_at->toDayDateTimeString(),
            $this->category2->created_at->toDayDateTimeString(),
            $this->category1->created_at->toDayDateTimeString(),
        ]);
});
