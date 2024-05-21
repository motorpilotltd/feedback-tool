<?php

use App\Livewire\GlobalSearch;
use App\Livewire\Modal\Search as ModalSearch;

use function Pest\Livewire\livewire;

beforeEach(function() {
    setupData();
});

it('renders the global search', function () {
    login()->get('/')
        ->assertSeeLivewire('modal.search')
        ->assertSeeLivewire('global-search');
});

it('opens the search modal and dispatches an event', function () {
    login()->livewire(ModalSearch::class)
        ->assertSet('showModal', false)
        ->call('openSearchModal')
        ->assertSet('showModal', true)
        ->assertDispatched('global-search-focuskeyword'); // Assert the event was dispatched
});

it('shows initial empty input keywords', function () {
    login()->livewire(GlobalSearch::class)
        ->assertViewHas('keywords', '');
});


it('shows the keywords typed in', function () {
    login()->livewire(GlobalSearch::class)
        ->set('keywords', 'test')
        ->assertViewHas('keywords', 'test');
});

it('shows no result when keyword didn\'t find any match', function () {
    login()->livewire(GlobalSearch::class)
        ->set('keywords', 'nomatchresult')
        ->assertSee(__('text.couldnotfind'));
});

it('shows correct result when keyword find match with highlighting', function () {
    $keyword = 'lorem';
    login()->livewire(GlobalSearch::class)
        ->set('keywords', $keyword)
        ->assertDontSee(__('text.couldnotfind'))
        ->assertSeeHtml(highlightMatchedSearch($this->idea1->title, $keyword));
});

it('doesn\'t show idea that don\'t match the  keyword', function () {
    login()->livewire(GlobalSearch::class)
        ->set('keywords', 'lorem')
        ->assertDontSee($this->idea2->title);
});
