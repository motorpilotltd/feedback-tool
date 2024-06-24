<?php

use App\Livewire\Forms\IdeaForm;
use App\Livewire\Idea\Card as IdeaCard;
use App\Livewire\Idea\VotesCountButton;
use App\Models\Idea;

use function Pest\Faker\fake;
use function Pest\Livewire\livewire;

beforeEach(function() {
    setupData();
});

it('can see vote button', function () {
    livewire(IdeaCard::class, ['idea' => $this->idea1->id])
        ->assertSeeLivewire('idea.votes-count-button');
});

it('can vote/unvote for the idea', function (bool $isVoting) {
    login();
    $user = auth()->user();
    $component = livewire(VotesCountButton::class, [
        'ideaId' => $this->idea1->id,
        'hasVoted' => $this->ideaVoteService->isVotedByUser($this->idea1, $user),
        'votesCount' => $this->idea1->votes()->count()
    ])
    ->assertViewHas('votesCount', 0);

    if ($isVoting) {
        $component->call('voteIdea')
            ->assertViewHas('votesCount', 1);
    } else {
        $component->call('voteIdea')
            ->assertViewHas('votesCount', 1);
    }

})->with([
    'voting' => true,
    'unvoting' => false,
]);

it('can automatically vote for the idea that the user created', function () {
    Idea::query()->delete();
    login()->livewire(IdeaForm::class, ['product' => $this->product1])
        ->set('title', fake()->text(20))
        ->set('category', $this->category1->id)
        ->set('content', fake()->text(50))
        ->call('saveIdea');

    $this->assertDatabaseHas('votes', [
        'idea_id' => Idea::first()->id,
        'user_id' => auth()->user()->id
    ]);
});
