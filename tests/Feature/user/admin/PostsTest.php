<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Events\Post\PostCreated;
use App\Events\Post\PostDeleted;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Utilities\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;

it('can view the create post page', function (): void {

    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.posts.create'))
        ->assertSuccessful();

});

it('cannot view the create post page', function (): void {

    $user = User::factory()->create(
        [
            'role' => Role::MEMBER->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.posts.create'))
        ->assertForbidden();

});

it('created the post', function (): void {

    Event::fake();

    $category = Category::factory()->create();
    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

    $this->actingAs($user)
        ->post(route('admin.posts.store'), [
            'title' => 'My first post',
            'excerpt' => 'My first post excerpt',
            'body' => 'My first post body',
            'thumbnail' => $thumbnail,
            'category_id' => $category->id,
        ])->assertRedirect(route('admin.posts.index'));

    $this->actingAs($user)
        ->post(route('admin.posts.store'), [
            'title' => 'My first post',
            'excerpt' => 'My first post excerpt',
            'body' => 'My first post body',
            'thumbnail' => $thumbnail,
            'category_id' => $category->id,
            'published_at' => now()->addWeek()->format('Y-m-d\TH:i'),
        ])->assertRedirect(route('admin.posts.index'));

    $this->assertDatabaseHas('posts', [
        'title' => 'My first post',
        'excerpt' => 'My first post excerpt',
        'body' => 'My first post body',
        'category_id' => $category->id,
        'thumbnail' => 'thumbnails/' . $thumbnail->hashName(),
    ]);

    $this->assertDatabaseCount('posts', 2);

    Event::assertDispatched(PostCreated::class);

});

it('can view posts', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.posts.index'))
        ->assertOk();
});

it('can not view posts', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::MEMBER->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.posts.index'))
        ->assertForbidden();
});

it('can delete a post', function (): void {

    Event::fake();

    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $post = Post::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.posts.destroy', $post->slug))
        ->assertRedirect(route('admin.posts.index'));

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);

    Event::assertDispatched(PostDeleted::class);

});

it('can not delete a post', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::MEMBER->value,
            'email_verified_at' => now(),
        ]
    );

    $post = Post::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.posts.destroy', $post->slug))
        ->assertForbidden();

});

it('can update a post', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $post = Post::factory()->create(
        [
            'title' => 'Old Title',
            'body' => 'Old Content',
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.posts.edit', $post->slug))
        ->assertOk();

    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

    $this->actingAs($user)
        ->patch(route('admin.posts.update', $post->slug), [
            'title' => 'New Title',
            'body' => 'New Content',
            'excerpt' => 'New Excerpt',
            'thumbnail' => $thumbnail,
        ])
        ->assertRedirect(route('admin.posts.index'));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'New Title',
        'body' => 'New Content',
        'excerpt' => 'New Excerpt',
        'thumbnail' => 'thumbnails/' . $thumbnail->hashName(),
    ]);
});

it('can not update a post', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::MEMBER->value,
            'email_verified_at' => now(),
        ]
    );

    $post = Post::factory()->create(
        [
            'title' => 'Old Title',
            'body' => 'Old Content',
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.posts.edit', $post->slug))
        ->assertForbidden();

    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

    $this->actingAs($user)
        ->patch(route('admin.posts.update', $post->slug), [
            'title' => 'New Title',
            'body' => 'New Content',
            'excerpt' => 'New Excerpt',
            'thumbnail' => $thumbnail,
        ])
        ->assertForbidden();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Old Title',
        'body' => 'Old Content',
    ]);
});
