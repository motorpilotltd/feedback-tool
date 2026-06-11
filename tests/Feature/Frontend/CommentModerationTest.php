<?php

use App\Livewire\Idea\Comment;
use App\Models\Comment as CommentModel;

beforeEach(function () {
    setupData();
    // idea1 lives in product1, which userProductAdmin1 manages.
    $this->comment = CommentModel::factory()->create([
        'idea_id' => $this->idea1->id,
        'user_id' => $this->userBasic->id,
    ]);
});

it('prevents a non-manager from pinning a comment', function () {
    login($this->userBasic)
        ->livewire(Comment::class, ['comment' => $this->comment, 'parentIdea' => $this->idea1])
        ->call('pinningComment', $this->comment->id, false);

    expect($this->idea1->fresh()->sticky_comment_id)->not->toBe($this->comment->id);
});

it('lets a product manager pin a comment', function () {
    login($this->userProductAdmin1)
        ->livewire(Comment::class, ['comment' => $this->comment, 'parentIdea' => $this->idea1])
        ->call('pinningComment', $this->comment->id, false);

    expect((int) $this->idea1->fresh()->sticky_comment_id)->toBe($this->comment->id);
});

it('prevents a non-manager from clearing spam reports', function () {
    $this->comment->spams()->attach($this->userSuperAdmin2->id);

    login($this->userBasic)
        ->livewire(Comment::class, ['comment' => $this->comment, 'parentIdea' => $this->idea1])
        ->call('commentNotSpamConfirm', $this->comment->id, true);

    expect($this->comment->spams()->count())->toBe(1);
});

it('lets a product manager clear spam reports', function () {
    $this->comment->spams()->attach($this->userSuperAdmin2->id);

    login($this->userProductAdmin1)
        ->livewire(Comment::class, ['comment' => $this->comment, 'parentIdea' => $this->idea1])
        ->call('commentNotSpamConfirm', $this->comment->id, true);

    expect($this->comment->spams()->count())->toBe(0);
});
