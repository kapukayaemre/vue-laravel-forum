<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('it can store a comment', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)
        ->post(route('posts.comments.store', $post), [
            'body' => 'test comment'
        ]);

    $this->assertDatabaseHas(Comment::class, [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body' => 'test comment'
    ]);
});

it('redirects to the post show page', function () {
    // Arrange
    $user = User::factory()->create();
    $post = Post::factory()->create();

    // Act & Assert
    actingAs($user)
        ->post(route('posts.comments.store', $post), [
            'body' => 'test comment'
        ])
        ->assertRedirect(route('posts.show', $post));
});

it('requires a valid body', function ($value) {
    // $this->withoutExceptionHandling();

    // Arrange
    $user = User::factory()->create();
    $post = Post::factory()->create();

    // Act & Assert
    actingAs($user)
        ->post(route('posts.comments.store', $post), [
            'body' => $value,
        ])
        ->assertInvalid('body');
})->with([
    null,
    1,
    1.5,
    true,
    str_repeat('a', 256)
]);

