<?php

use App\Livewire\Admin\IdeasTable;
use Illuminate\Support\Str;

beforeEach(function () {
    setupData();
    test()->idea1TitleTable = Str::limit($this->idea1->title, 35, '...');
    test()->idea2TitleTable = Str::limit($this->idea2->title, 35, '...');
    test()->idea21TitleTable = Str::limit($this->idea21->title, 35, '...');
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
        ->call('setProductSelected', $this->product1->id)
        ->set('filters.search', $this->searchString)
        ->assertSee($this->idea1TitleTable);
});

it('can filter ideas by status', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('filters.statuses', [$this->status1->slug])
        ->assertSee($this->status1->name)
        ->assertSee($this->idea1TitleTable);
});

it('can filter ideas by category', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('filters.categories', [$this->category1->slug])
        ->assertSee($this->category1->name)
        ->assertSee($this->idea1TitleTable)
        ->assertDontSee($this->idea2TitleTable);
});

it('can reset filters', function () {

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('filters.statuses', [$this->status1->slug])
        ->assertSee($this->idea1TitleTable)
        ->assertDontSee($this->idea2TitleTable)
        ->call('resetFilters')
        ->assertSee($this->idea2TitleTable)
        ->set('filters.categories', [$this->category2->slug])
        ->assertDontSee($this->idea1TitleTable)
        ->assertSee($this->idea2TitleTable)
        ->call('resetFilters')
        ->set('productId', $this->product1->id)
        ->assertSee($this->idea1TitleTable)
        ->assertSee($this->idea2TitleTable);
});

it('can select all ideas', function () {

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('selectAll', true)
        ->assertCount('selected', $this->product1->ideas->count());
});

it('can calculate WSJF for an idea', function () {

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->call('calculate', $this->idea1->id)
        ->set('calcData.business_value', 3)
        ->set('calcData.time_criticality', 2)
        ->set('calcData.complexity', 1)
        ->call('saveCalculate')
        ->assertSet('wsjf', 5.0);

    $this->assertDatabaseHas('ideas', [
        'id' => $this->idea1->id,
        'wsjf' => 5.0,
    ]);
});

it('can move an idea to a different category', function () {

    $this->assertDatabaseHas('ideas', [
        'id' => $this->idea1->id,
        'category_id' => $this->category1->id,
    ]);

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('move', $this->idea1->id)
        ->set('selectedCategory', $this->category2->id)
        ->call('saveMove')
        ->assertSet('editing.category_id', $this->category2->id);

    $this->assertDatabaseHas('ideas', [
        'id' => $this->idea1->id,
        'category_id' => $this->category2->id,
    ]);
});

it('can sort ideas by id', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'ideas.id')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->idea1TitleTable,
            $this->idea2TitleTable,
            $this->idea21TitleTable,
        ]);

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'ideas.id')
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->idea21TitleTable,
            $this->idea2TitleTable,
            $this->idea1TitleTable,
        ]);
});

it('can sort ideas by title', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'ideas.title')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->idea1TitleTable,
            $this->idea2TitleTable,
            $this->idea21TitleTable,
        ]);

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'ideas.title')
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->idea21TitleTable,
            $this->idea2TitleTable,
            $this->idea1TitleTable,
        ]);
});

it('can sort ideas by category', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'category.name')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->idea1TitleTable,
            $this->idea2TitleTable,
            $this->idea21TitleTable,
        ]);

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'category.name')
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->idea21TitleTable,
            $this->idea2TitleTable,
            $this->idea1TitleTable,
        ]);
});

it('can sort ideas by status', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'status.name')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->idea1TitleTable,
            $this->idea2TitleTable,
            $this->idea21TitleTable,
        ]);

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'status.name')
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->idea21TitleTable,
            $this->idea2TitleTable,
            $this->idea1TitleTable,
        ]);
});

it('can sort ideas by Added By User', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'addedByUser.name')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->idea1TitleTable,
            $this->idea2TitleTable,
            $this->idea21TitleTable,
        ]);

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'addedByUser.name')
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->idea21TitleTable,
            $this->idea2TitleTable,
            $this->idea1TitleTable,
        ]);
});

it('can sort ideas by created date', function () {
    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'created_at')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder([
            $this->idea1TitleTable,
            $this->idea2TitleTable,
            $this->idea21TitleTable,
        ]);

    login($this->userSuperAdmin)
        ->livewire(IdeasTable::class)
        ->call('setProductSelected', $this->product1->id)
        ->set('sortField', 'created_at')
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder([
            $this->idea21TitleTable,
            $this->idea2TitleTable,
            $this->idea1TitleTable,
        ]);
});
