<?php

use App\Models\Category;
use App\Models\Idea;
use App\Models\Product;
use App\Models\User;

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

it('redirects to /login when forcelogin was enabled', function () {
    setupGeneralSettings();
    $this->generalSettings->forcelogin = true;

    $this->get('/')
        ->assertStatus(302)
        ->assertRedirect('/login');
});

it('can see login and register link when user not logged in', function () {
    $this->get('/')
        ->assertSee('Login')
        ->assertSee('Register');
});

it('hides login and register link when user logged in', function () {
    login()->get('/')
        ->assertDontSee('Login')
        ->assertDontSee('Register');
});

it('can see user dropdown menu when user logged in', function () {
    login()->get('/')
        ->assertSee('user-dropdown-nav');
});

it('cannot see user dropdown menu when user not logged in', function () {
    $this->get('/')
        ->assertDontSee('user-dropdown-nav');
});

it('can see notification bell when user logged in', function () {
    login()->get('/')
        ->assertSeeLivewire('notification-bell');
});

it('cannot see notification bell when user not logged in', function () {
    $this->get('/')
        ->assertDontSeeLivewire('notification-bell');
});

// @TODO: test for sandbox and permission indicators on products
