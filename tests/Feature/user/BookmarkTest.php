<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\Post;
use App\Models\User;

it('can view his bookmarks', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('my-bookmarks'))
        ->assertOk();
});

it('can not view bookmarks', function (): void {
    $response = $this->get(route('my-bookmarks'));

    $response->assertRedirect(route('login'));
});

it('can bookmark a post', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $this->actingAs($user)
        ->post(route('bookmark.store', $post->slug))
        ->assertRedirect()->with('success', 'Post bookmarked');

    expect($user->bookmarks()->count())->toBe(1);
});

it('can not bookmark a post twice', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $user->bookmarks()->create([
        'post_id' => $post->id,
    ]);

    $this->actingAs($user)
        ->post(route('bookmark.store', $post->slug))
        ->assertRedirect()->with('error', 'Post already bookmarked');

    expect($user->bookmarks()->count())->toBe(1);

});

it('can delete a bookmark', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $user->bookmarks()->create([
        'post_id' => $post->id,
    ]);

    $this->actingAs($user)
        ->delete(route('bookmark.destroy', $post->slug))
        ->assertRedirect()->with('success', 'Bookmark deleted');

    expect($user->bookmarks()->count())->toBe(0);
});
