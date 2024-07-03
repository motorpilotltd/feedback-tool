<?php

use App\Livewire\Admin\IdeasTable;
use Illuminate\Support\Str;

beforeEach(function () {
    setupData();
    test()->idea1TitleTable = Str::limit($this->idea1->title, 35, '...');
});

it('renders the IdeasTable component', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->assertStatus(200);
});

it('shows a warning when there are no ideas', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->set('productId', null)
        ->assertSee(__('text.noideaavail'));
});

it('can search for ideas', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->set('filters.search', $this->searchString)
        ->set('productId', $this->product1->id)
        ->assertSee($this->idea1TitleTable);
});

it('can filter ideas by status', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->set('filters.statuses', [$this->status1->slug])
        ->set('productId', $this->product1->id)
        ->assertSee($this->status1->name)
        ->assertSee($this->idea1TitleTable);
});
