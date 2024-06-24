<?php

use App\Livewire\Idea\IdeaShow;

use function Pest\Livewire\livewire;

beforeEach(function () {
    setupData();
});

it('shows the idea\'s details in the idea show page when visiting idea link', function () {
    $idea = $this->idea1;
    $this->get($idea->idea_link)
        ->assertOk()
        ->assertSee($idea->title);
});

it('shows 404 when visiting a non-existing idea link', function () {
    $idea = $this->idea1;
    $this->get($idea->idea_link.'test')
        ->assertStatus(404);
});

it('shows edit idea link/button when current logged in user is the author', function () {
    login();
    livewire(IdeaShow::class, ['idea' => createIdeaWithUser(auth()->user())])
        ->assertSee(__('text.editidea'));
});

it('shows delete idea link/button when current logged in user is the author', function () {
    login();

    livewire(IdeaShow::class, ['idea' => createIdeaWithUser(auth()->user())])
        ->assertSee(__('text.deleteidea'));
});

it('shows Delete Idea link/button when user is super user ', function () {
    login($this->userSuperAdmin);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertSee(__('text.deleteidea'));
});

it('does not shows Delete Idea link/button when logged in user is not an admin ', function () {
    login($this->userBasic);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertDontSee(__('text.deleteidea'));
});

it('shows Delete Idea link/button when user is a product admin of the idea\'s product ', function () {
    login($this->userProductAdmin1);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertSee(__('text.deleteidea'));
});

it('does not shows Delete Idea link/button when logged in user is not a product admin to the idea\'s product', function () {
    login($this->userProductAdmin2);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertDontSee(__('text.deleteidea'));
});

it('shows "Not a Spam" link/button when logged in user is super user for idea that has been marked as spam', function () {
    login($this->userSuperAdmin);
    $this->ideaSpamService->toggleSpam($this->idea1, $this->userBasic);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertSee(__('text.notaspam'));
});

it('does not shows "Not a Spam" link/button when logged in user is not admin user for idea that has been marked as spam', function () {
    login($this->userBasic);
    $this->ideaSpamService->toggleSpam($this->idea1, $this->userSuperAdmin);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertDontSee(__('text.notaspam'));
});

it('shows "Not a Spam" link/button when logged in user is product admin for idea\'s product that has been marked as spam', function () {
    login($this->userProductAdmin1);
    $this->ideaSpamService->toggleSpam($this->idea1, $this->userBasic);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertSee(__('text.notaspam'));
});

it('does not shows "Not a Spam" link/button when logged in user is not product admin for idea\'s product that has been marked as spam', function () {
    login($this->userProductAdmin2);
    $this->ideaSpamService->toggleSpam($this->idea1, $this->userBasic);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertDontSee(__('text.notaspam'));
});

it('shows "Spam reports" count when logged in user is super user for idea that has been marked as spam', function () {
    login($this->userSuperAdmin);
    $this->ideaSpamService->toggleSpam($this->idea1, $this->userBasic);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertSee(__('text.spamreportcount', ['count' => 1]));
});

it('does not shows "Spam reports" count when logged in user is not an admin user for idea that has been marked as spam', function () {
    login($this->userBasic);
    $this->ideaSpamService->toggleSpam($this->idea1, $this->userSuperAdmin);
    livewire(IdeaShow::class, ['idea' => $this->idea1])
        ->assertDontSee(__('text.spamreportcount', ['count' => 1]));
});
