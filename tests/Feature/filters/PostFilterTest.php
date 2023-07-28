<?php

declare(strict_types=1);

namespace Tests\Feature\filters;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

test('Filter post works as expected', function (): void {
    $john = User::factory()->create(['username' => 'john']);
    $jane = User::factory()->create(['username' => 'jane']);

    $foodCategory = Category::factory()->create(['name' => 'food']);
    $sportCategory = Category::factory()->create(['name' => 'sport']);

    $post1 = Post::factory()->create([
        'user_id' => $john->id,
        'category_id' => $foodCategory->id,
        'title' => 'John Food Post',
        'body' => 'A post about food'
    ]);

    $post2 = Post::factory()->create([
        'user_id' => $jane->id,
        'category_id' => $sportCategory->id,
        'title' => 'Jane Sport Post',
        'body' => 'A post about sport'
    ]);

    $filteredPosts = Post::filter(['search' => 'food'])->get();

    expect($filteredPosts->contains($post1))
        ->and($filteredPosts->contains($post2))->toBeFalse();

    $filteredPosts = Post::filter(['category' => 'sport'])->get();
    expect($filteredPosts->contains($post2))
        ->and($filteredPosts->contains($post1))->toBeFalse();

    $filteredPosts = Post::filter(['author' => 'jane'])->get();
    expect($filteredPosts->contains($post2))
        ->and($filteredPosts->contains($post1))->toBeFalse();

});
