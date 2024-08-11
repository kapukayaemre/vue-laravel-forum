<?php


use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->validData = [
        'title' => 'Hello World',
        'body' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit maecenas nisl enim, nostra posuere rutrum facilisis vitae condimentum auctor malesuada nullam mollis congue, nam sagittis eleifend dis quam risus vulputate sed fames. Ac leo auctor taciti erat sed quis elementum metus per arcu, viverra venenatis curae dignissim cursus massa porttitor quisque nascetur, himenaeos augue laoreet netus pretium montes sodales maecenas class. Rhoncus pharetra odio ornare sociosqu facilisis platea hendrerit, semper nibh magnis sed porttitor proin, diam mauris eget montes condimentum himenaeos. Donec nullam facilisis litora luctus nascetur, duis mi quam purus enim torquent, suscipit felis inceptos pharetra.'
    ];
});

it('requires authentication', function () {
    post(route('posts.store'))
        ->assertRedirect(route('login'));
});

it('stores a post', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), $this->validData);

    $this->assertDatabaseHas(Post::class, [
        ...$this->validData,
        'user_id' => $user->id
    ]);
});

it('redirects to the post show page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), $this->validData)
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires a valid data', function (array $badData, array|string $errors) {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), [...$this->validData, ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => true], 'title'],
    [['title' => 1], 'title'],
    [['title' => 1.5], 'title'],
    [['title' => str_repeat('a', 121)], 'title'],
    [['title' => str_repeat('a', 9)], 'title'],
    [['body' => null], 'body'],
    [['body' => true], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => str_repeat('a', 10_001)], 'body'],
    [['body' => str_repeat('a', 99)], 'body']
]);

/*it('requires a valid body', function ($badBody) {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), [...$this->validData, 'body' => $badBody])
        ->assertInvalid('body');
})->with([
    null,
    true,
    1,
    1.5,
    str_repeat('a', 10001),
    str_repeat('a', 99)
]);*/
