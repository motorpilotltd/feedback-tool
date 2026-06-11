<?php

use App\Livewire\NotificationBell;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

it('does not let a user delete another user\'s notification', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $notification = $userB->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'App\\Notifications\\IdeaAdded',
        'data' => ['idea_id' => 1],
    ]);

    // Scoped to the actor's own notifications, so another user's id is not found
    // (a 404 in the real HTTP flow) and cannot be deleted.
    expect(fn () => login($userA)
        ->livewire(NotificationBell::class)
        ->call('markAsRead', $notification->id)
    )->toThrow(ModelNotFoundException::class);

    expect(DatabaseNotification::find($notification->id))->not->toBeNull();
});
