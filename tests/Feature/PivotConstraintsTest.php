<?php

use App\Models\Comment as CommentModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    setupData();
});

it('enforces a unique spam report per idea and user', function () {
    DB::table('idea_spam')->insert(['idea_id' => $this->idea1->id, 'user_id' => $this->userBasic->id]);

    expect(fn () => DB::table('idea_spam')->insert([
        'idea_id' => $this->idea1->id,
        'user_id' => $this->userBasic->id,
    ]))->toThrow(QueryException::class);
});

it('enforces a unique spam report per comment and user', function () {
    $comment = CommentModel::factory()->create([
        'idea_id' => $this->idea1->id,
        'user_id' => $this->userBasic->id,
    ]);

    DB::table('comment_spam')->insert(['comment_id' => $comment->id, 'user_id' => $this->userBasic->id]);

    expect(fn () => DB::table('comment_spam')->insert([
        'comment_id' => $comment->id,
        'user_id' => $this->userBasic->id,
    ]))->toThrow(QueryException::class);
});
