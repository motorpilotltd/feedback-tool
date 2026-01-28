<?php

use App\Livewire\Forms\IdeaForm;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Product;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->product1 = Product::factory()->create(['name' => 'A product name']);
    // Categories
    $this->category1 = Category::factory()->create(['product_id' => $this->product1]);
    // Ideas
    $this->idea1 = Idea::factory()->create([
        'title' => 'Lorem ipsum dolor sit amet',
        'category_id' => $this->category1,
    ]);
    $this->idea2 = Idea::factory()->create([
        'title' => 'Nullam luctus mi ac',
        'category_id' => $this->category1,
    ]);

    $this->searchString = 'Lorem luctus';
});

it('redirects to login when user try to visit suggest idea form page without logging in', function () {
    $this->get(route('product.suggest.idea', $this->product1))
        ->assertStatus(302)
        ->assertRedirect('/login');
});

it('can validate suggest idea form for errors', function () {
    login()->livewire(IdeaForm::class, ['product' => $this->product1])
        ->set('title', '')
        ->set('category', '')
        ->set('content', '')
        ->call('saveIdea')
        ->assertHasErrors(['title', 'category', 'content'])
        ->set('title', 'Testing Title')
        ->set('category', $this->category1->id)
        ->set('content', 'Testing content text')
        ->call('saveIdea')
        ->assertHasNoErrors(['title', 'category', 'content'])
        ->set('category', 11111)
        ->call('saveIdea')
        ->assertHasErrors(['category']);
});

it('can display correct idea details when editing in the idea form', function () {
    $idea = createIdeaWithUser(auth()->user(), $this->product1);
    login()->livewire(IdeaForm::class, ['product' => $this->product1, 'idea' => $idea])
        ->call('setIdeaFormData')
        ->assertViewHas('formTitle', __('text.ideaformtitle:update'))
        ->assertSet('title', $idea->title)
        ->assertSet('category', $idea->category->id)
        ->assertSet('content', $idea->content);
});

it('can save new suggested idea when no errors', function () {
    login()->livewire(IdeaForm::class, ['product' => $this->product1])
        ->set('title', fake()->text(20))
        ->set('category', $this->category1->id)
        ->set('content', fake()->text(50))
        ->call('saveIdea')
        ->assertHasNoErrors(['title', 'category', 'content'])
        ->assertStatus(200)
        ->assertSessionHas('notify.type', 'success')
        ->assertSessionHas('notify.message', __('text.createideasuccess'));
});

it('can save editing idea when current logged in user is the author', function () {
    login();
    $idea = createIdeaWithUser(auth()->user(), $this->product1);
    $title = fake()->text(20);
    $content = fake()->text(50);
    livewire(IdeaForm::class, ['product' => $this->product1, 'idea' => $idea])
        ->call('setIdeaFormData')
        ->set('title', $title)
        ->set('content', $content)
        ->call('saveIdea')
        ->assertStatus(200)
        ->assertSessionHas('notify.type', 'success')
        ->assertSessionHas('notify.message', __('text.ideaupdatesuccess'));

    $this->assertDatabaseHas('ideas', [
        'id' => $idea->id,
        'title' => $title,
        'content' => $content,
    ]);
});

it('can see search string as idea title in the Suggest Idea form', function () {
    $this->withSession(['suggestIdeaTitle' => $this->searchString]);
    login()->livewire(IdeaForm::class, ['product' => $this->product1])
        ->assertViewHas('title', $this->searchString);
});

it('can prevent save editing idea when current logged in user was not the author', function () {
    login();
    $idea = createIdeaWithUser(User::factory()->create());
    livewire(IdeaForm::class, ['product' => $this->product1, 'idea' => $idea])
        ->call('setIdeaFormData')
        ->set('title', fake()->text(20))
        ->call('saveIdea')
        ->assertStatus(200)
        ->assertSessionMissing('notify.type', 'success')
        ->assertDispatched('saveidea-unauthorized');
});

it('can redirect to idea page after saving', function () {
    Idea::query()->delete();
    login()->livewire(IdeaForm::class, ['product' => $this->product1])
        ->set('title', fake()->text(20))
        ->set('category', $this->category1->id)
        ->set('content', fake()->text(50))
        ->call('saveIdea')
        ->assertRedirectToRoute('idea.show', Idea::first());
});
