<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\Post;
use App\Models\User;

it('can comment on a post', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $this->actingAs($user)
        ->post(route('posts.comments.store', $post), ['body' => 'This is a comment'])
        ->assertRedirect();

    $this->assertDatabaseHas('comments', ['body' => 'This is a comment']);

    expect($post->comments()->count())->toBe(1);

    $this->get(route('posts.show', $post))
        ->assertSuccessful()
        ->assertSeeText('This is a comment');
});

it('can not comment on a post if not logged in', function (): void {
    $post = Post::factory()->create();

    $this->get(route('posts.show', $post))
        ->assertSuccessful()
        ->assertDontSeeText('Want to participate?');

    $this->post(route('posts.comments.store', $post), ['body' => 'This is a comment'])
        ->assertRedirect(route('login'));

    $this->assertDatabaseMissing('comments', ['body' => 'This is a comment']);
});
