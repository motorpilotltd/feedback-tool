<?php

use App\Livewire\Forms\IdeaForm;
use App\Models\Idea;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->product = Product::factory()->create();
    // Product creation seeds a default category via the model's created hook.
    $this->category = $this->product->categories()->first();
});

it('ignores a tampered authorId for a user without specifyAuthor', function () {
    $actor = User::factory()->create();
    $other = User::factory()->create();

    login($actor)->livewire(IdeaForm::class, ['product' => $this->product])
        ->set('title', 'A tampered idea')
        ->set('category', $this->category->id)
        ->set('content', 'Some idea content here')
        ->set('authorId', $other->id)
        ->call('saveIdea')
        ->assertStatus(200);

    $idea = Idea::where('title', 'A tampered idea')->first();

    expect($idea)->not->toBeNull()
        ->and((int) $idea->author_id)->toBe($actor->id);
});

it('does not create an on-behalf account for a user without specifyAuthor', function () {
    $actor = User::factory()->create();

    login($actor)->livewire(IdeaForm::class, ['product' => $this->product])
        ->set('title', 'No new account idea')
        ->set('category', $this->category->id)
        ->set('content', 'Some idea content here')
        ->set('newUser', ['name' => 'Injected Person', 'email' => 'injected@example.com'])
        ->call('saveIdea')
        ->assertStatus(200);

    $this->assertDatabaseMissing('users', ['email' => 'injected@example.com']);
});
