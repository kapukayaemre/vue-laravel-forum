<?php

use App\Models\Comment;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

it('requires authentication', function () {
    $comment = Comment::factory()->create();

    put(route('comments.update', $comment))
        ->assertRedirect(route('login'));
});

it('can update a comment', function () {
    $comment = Comment::factory()->create([
        'body' => 'This is the old body'
    ]);

    $newComment = 'This is updated body';

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => $newComment]);

    $this->assertDatabaseHas(Comment::class, [
        'id'   => $comment->id,
        'body' => $newComment
    ]);
});

it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => 'This is updated body'])
        ->assertRedirect(route('posts.show', $comment->post));

});

it('redirects to the correct page of the comments', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]),
            ['body' => 'This is updated body'])
        ->assertRedirect(route('posts.show', ['post' => $comment->post, 'page' => 2]));
});

it('cannot update a comment from another user', function () {
    $comment = Comment::factory()->create(['body' => 'my comment']);
    $user = User::factory()->create();

    actingAs($user)
        ->put(route('comments.update', $comment), ['body' => 'This is updated body'])
        ->assertForbidden();

});

it('requires a valid body', function ($body) {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => $body])
        ->assertInvalid('body');
})->with([
    null,
    1,
    1.5,
    true,
    str_repeat('a', 2501)
]);
