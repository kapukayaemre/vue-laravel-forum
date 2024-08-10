<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('posts.create'))
        ->assertRedirect(route('login'));
});

it('returns to correct component', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});
