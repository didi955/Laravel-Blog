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

    var_dump(Post::filter(['search' => 'food'])->get());

    $filteredPosts = Post::filter(['search' => 'food'])->get();
    expect($filteredPosts)->toContain($post1)
        ->and($filteredPosts)->not()->toContain($post2);

    $filteredPosts = Post::filter(['category' => 'sport'])->get();
    expect($filteredPosts)->toContain($post2)
        ->and($filteredPosts)->not()->toContain($post1);

    $filteredPosts = Post::filter(['author' => 'john'])->get();
    expect($filteredPosts)->toContain($post1)
        ->and($filteredPosts)->not()->toContain($post2);

});
