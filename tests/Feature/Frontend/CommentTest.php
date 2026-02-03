<?php

use App\Livewire\Forms\CommentForm;
use App\Livewire\Idea\Card as IdeaCard;
use App\Livewire\Idea\CommentsContainer;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

beforeEach(function () {
    setupData();
    $this->comment1 = Comment::create([
        'user_id' => User::factory()->create()->id,
        'idea_id' => $this->idea1->id,
        'content' => fake()->text(20),
        'created_at' => Carbon::now()->subDays(2),
    ]);

    $this->comment2 = Comment::create([
        'user_id' => User::factory()->create()->id,
        'idea_id' => $this->idea2->id,
        'content' => fake()->text(20),
    ]);
});

it('can see comments count in the idea card rendered for idea listing', function () {
    livewire(IdeaCard::class, ['idea' => $this->idea1->id])
        ->assertSee(Str::plural(__('general.commentcount', ['count' => 1]), 1));
});

it('can see comment in the idea page', function () {
    $idea = $this->idea1;
    $this->get($idea->idea_link)
        ->assertSee($this->comment1->content);
});

it('does not show comments that\'s not belong to the idea', function () {
    $idea = $this->idea1;
    $this->get($idea->idea_link)
        ->assertSee($this->comment1->content)
        ->assertDontSee($this->comment2->content);
});

it('can validate content on comment form for errors', function () {

    $imageFile = UploadedFile::fake()->image('testimage.png', 2, 2);

    login()->livewire(CommentForm::class, ['idea' => $this->idea1, 'action' => 'addComment'])
        ->set('content', '')
        ->set('attachments', [$imageFile])
        ->call('addComment')
        ->assertHasErrors(['content'])
        ->assertHasNoErrors(['attachments']);
});

it('can validate attachments on comment form for errors', function () {

    $textFile = UploadedFile::fake()->create('testfile.txt', 5, 'text/plain');
    $testContent = fake()->text(50);

    login()->livewire(CommentForm::class, ['idea' => $this->idea1, 'action' => 'addComment'])
        ->set('content', $testContent)
        ->set('attachments', [$textFile])
        ->call('addComment')
        ->assertHasErrors(['attachments'])
        ->assertHasNoErrors(['content']);
});

it('can validate content and attachments on comment form for errors', function () {

    $textFile = UploadedFile::fake()->create('testfile.txt', 5, 'text/plain');

    login()->livewire(CommentForm::class, ['idea' => $this->idea1, 'action' => 'addComment'])
        ->set('content', '')
        ->set('attachments', [$textFile])
        ->call('addComment')
        ->assertHasErrors(['content', 'attachments']);
});

it('can validate content and attachments on comment form when no errors', function () {

    $imageFile = UploadedFile::fake()->image('testimage.png', 2, 2);
    $testContent = fake()->text(50);

    login()->livewire(CommentForm::class, ['idea' => $this->idea1, 'action' => 'addComment'])
        ->set('content', $testContent)
        ->set('attachments', [$imageFile])
        ->call('addComment')
        ->assertHasNoErrors(['content', 'attachments']);
});

it('can show newly added comment as the latest', function () {
    $testContent = fake()->text(50);
    $component = login()->livewire(CommentsContainer::class, ['idea' => $this->idea1]);

    $this->assertEquals(1, $component->comments->count());

    login()->livewire(CommentForm::class, ['idea' => $this->idea1, 'action' => 'addComment'])
        ->set('content', $testContent)
        ->call('addComment')
        ->assertHasNoErrors(['content']);

    $component = login()->livewire(CommentsContainer::class, ['idea' => $this->idea1]);
    $comments = $component->comments;
    $this->assertEquals(2, $comments->count());
    $this->assertEquals($comments->first()->content, $testContent);
});

it('can show newly added comment\'s attachment', function () {
    $testContent = fake()->text(50);
    $imageFile = UploadedFile::fake()->image('testimage.png', 2, 2);
    login()->livewire(CommentForm::class, ['idea' => $this->idea1, 'action' => 'addComment'])
        ->set('content', $testContent)
        ->set('attachments', [$imageFile])
        ->call('addComment')
        ->assertHasNoErrors(['content', 'attachments']);

    $component = login()->livewire(CommentsContainer::class, ['idea' => $this->idea1]);
    $commentFirst = $component->comments->first();
    $this->assertEquals(1, $commentFirst->getMedia('attachments')->count() === 1);
});
