<?php

declare(strict_types=1);

namespace Tests\Feature\pages;

use App\Models\Post;
use App\Utilities\PostStatus;

test('home page can be rendered', function (): void {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('published post is displayed on home page', function (): void {
    $post = Post::factory()->create();

    $response = $this->get(route('home'));
    $response->assertSeeText($post->title);

});

test('pending & draft post can not be displayed', function (): void {
    $post = Post::factory()->create([
        'status' => PostStatus::PENDING->value,
    ]);
    $response = $this->get(route('home'));
    $response->assertDontSeeText($post->title);

    $post = Post::factory()->create([
        'status' => PostStatus::DRAFT->value,
    ]);
    $response = $this->get(route('home'));
    $response->assertDontSeeText($post->title);

});
