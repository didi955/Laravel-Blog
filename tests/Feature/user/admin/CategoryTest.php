<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Utilities\Role;

it('can view categories', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertOk();
});

it('can not view categories', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::MEMBER->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertForbidden();

    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => null,
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertRedirect(route('verification.notice'));

});

it('can create new category', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertOk();

    $this->actingAs($user)
        ->post(route('admin.categories.store'), ['name' => 'Category'])
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', ['name' => 'Category']);

});

it('can not create new category', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::MEMBER->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertForbidden();

    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => null,
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertRedirect(route('verification.notice'));

    $this->actingAs($user)
        ->post(route('admin.categories.store'), ['name' => 'Category'])
        ->assertRedirect();

    $this->assertDatabaseMissing('categories', ['name' => 'Category']);

});

it('can delete a category', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $categoryName = 'Category';

    $category = Category::factory()->create(
        [
            'name' => $categoryName,
        ]
    );

    $this->assertDatabaseHas('categories', ['name' => $categoryName]);

    $this->actingAs($user)
        ->delete(route('admin.categories.destroy', $category->slug))
        ->assertRedirect();

    $this->assertDatabaseMissing('categories', ['name' => $categoryName]);

});

test('Category Relation', function (): void {
    $category = Category::factory()->create();

    $post = Post::factory()->create();

    $category->posts()->save($post);

    expect($category->posts()->first()->category->name)->toBe($category->name)
        ->and($category->posts()
            ->first()->slug)
        ->toBe($post->slug);
});
