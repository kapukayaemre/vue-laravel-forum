<?php

use App\Models\Post;

it('uses title case for title', function () {
    $post = Post::factory()->create(['title' => 'Hello, how are you?']);

    expect($post->title)->toBe('Hello, How Are You?');
});
