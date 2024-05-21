<?php

use App\Livewire\Idea\IdeaCardsContainer;

use function Pest\Livewire\livewire;
use Livewire\Livewire;

beforeEach(function() {
    setupData();
});

it('can show post new idea button after searchign when user logged in', function () {
    login()->livewire(IdeaCardsContainer::class, ['product' => $this->product1])
        ->set('search',  $this->searchString)
        ->assertSee(__('general.post_new_idea'))
        ->assertViewHas('searchTitle', $this->searchString);
});

it('doesn\'t show post new idea button when no user logged in', function () {
    livewire(IdeaCardsContainer::class, ['product' => $this->product1])
        ->set('search', $this->searchString)
        ->assertDontSee(__('general.post_new_idea'));
});

it('can show \"Vote from existing idea(s)\" when there are results from search', function () {
    login()->livewire(IdeaCardsContainer::class, ['product' => $this->product1])
        ->set('search', $this->searchString)
        ->assertSee(__('general.vote_from_existing'));
});


it('redirects to suggest idea page when clicking "Post a new idea" button', function () {
    login()->livewire(IdeaCardsContainer::class, ['product' => $this->product1])
        ->set('search',  $this->searchString)
        ->assertViewHas('searchTitle', $this->searchString)
        ->call('suggestingIdea', $this->searchString)
        ->assertOk()
        ->assertRedirect(route('product.suggest.idea', $this->product1));
});

it('does not show the idea that doesn\'t belong to the current product', function () {
    login()->livewire(IdeaCardsContainer::class, ['product' => $this->product1])
    ->assertSee($this->idea2->title)
    ->assertDontSee($this->idea4->title);
});



it('can show filtered idea when a category was selected in the dropdown', function () {
    //expect()->
    login()->livewire(IdeaCardsContainer::class, ['product' => $this->product1])
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 3;
        })
        ->assertSee($this->idea1->title)
        ->assertSee($this->idea2->title)
        ->set('category', $this->category1->slug)
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 1;
        })
        // Failing on Livewire 3
        // when asserting string that is rendered in child nested component
        // ->assertSee($this->idea1->title)
        // An alternative for above failing assertSee
        ->assertViewHas('ideas', function ($ideas) {
            return in_array($this->idea1->title, $ideas->pluck('title')->toArray());
        })
        ->assertDontSee($this->idea2->title);
});

it('shows no result when selecting a category with no idea', function () {
    login()->livewire(IdeaCardsContainer::class, ['product' => $this->product1])
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 3;
        })
        ->set('category', $this->category3->slug)
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 0;
        })
        ->assertDontSee($this->idea1->title)
        ->assertDontSee($this->idea2->title);
});

it('can filter ideas by status', function () {
    login()->livewire(IdeaCardsContainer::class, ['product' => $this->product1])
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 3;
        })
        ->set('status', $this->status1->slug)
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 1;
        })
        // Failing on Livewire 3
        // when asserting string that is rendered in child nested component
        // ->assertSee($this->idea1->title)
        // An alternative for above failing assertSee
        ->assertViewHas('ideas', function ($ideas) {
            return in_array($this->idea1->title, $ideas->pluck('title')->toArray());
        })
        ->assertDontSee($this->idea2->title);
});

it('shows no result when selecting a status with no idea', function () {
    login()->livewire(IdeaCardsContainer::class, ['product' => $this->product1])
        ->set('status', $this->status2->slug)
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 0;
        })
        ->assertDontSee($this->idea1->title)
        ->assertDontSee($this->idea2->title);
});

it('shows matching results when searching idea that matched the keywords', function () {
    login();
    Livewire::test(IdeaCardsContainer::class, ['product' => $this->product1])
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 3;
        })
        ->set('search', $this->searchString)
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 1;
        })
        // Failing on Livewire 3
        // when asserting string that is rendered in child nested component
        // ->assertSee($this->idea1->title)
        // An alternative for failing assertSee
        ->assertViewHas('ideas', function ($ideas) {
            return in_array($this->idea1->title, $ideas->pluck('title')->toArray());
        })
        ->assertViewHas('ideas', function ($ideas) {
            return in_array($this->idea1->title, $ideas->pluck('title')->toArray());
        });
});


it('does not show results when searching idea that doesn\'t match the keywords provided', function () {
    login()->livewire(IdeaCardsContainer::class, ['product' => $this->product1])
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 3;
        })
        ->set('search', 'FOOBAR')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() === 0;
        })
        ->assertDontSee($this->idea1->title)
        ->assertDontSee($this->idea2->title)
        ->assertSee(__('general.noitemsfound', ['items' => 'ideas']));
});

// @TODO: to test special filters such as top, new, trending etc.
