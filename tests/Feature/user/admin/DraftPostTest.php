<?php

declare(strict_types=1);

namespace Tests\Feature\user\admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Utilities\PostStatus;
use App\Utilities\Role;
use Illuminate\Http\UploadedFile;

it('can create a draft post', function (): void {

    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $category = Category::factory()->create();

    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

    $this->actingAs($user)
        ->post(route('admin.posts.draft.store'), [
            'title' => 'My first post',
            'excerpt' => 'My first post excerpt',
            'body' => 'My first post body',
            'thumbnail' => $thumbnail,
            'category_id' => $category->id,
            'published_at' => now()->addWeek()->format('Y-m-d\TH:i')

        ])->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'title' => 'My first post',
        'excerpt' => 'My first post excerpt',
        'body' => 'My first post body',
        'thumbnail' => 'thumbnails/' . $thumbnail->hashName(),
        'category_id' => $category->id,
        'status' => PostStatus::DRAFT->value,
    ]);

});

it('can not create a draft post', function (): void {

    $user = User::factory()->create(
        [
            'role' => Role::MEMBER->value,
            'email_verified_at' => now(),
        ]
    );

    $category = Category::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.posts.draft.store'), [
            'title' => 'My first post',
            'excerpt' => 'My first post excerpt',
            'body' => 'My first post body',
            'category_id' => $category->id,
            'published_at' => null,

        ])->assertForbidden();

    $this->assertDatabaseMissing('posts', [
        'title' => 'My first post',
        'excerpt' => 'My first post excerpt',
        'body' => 'My first post body',
        'category_id' => $category->id,
        'published_at' => null,
        'status' => PostStatus::DRAFT->value,
    ]);

});

it('can update a draft post', function (): void {

    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $post = Post::factory()->create(
        [
            'user_id' => $user->id,
            'status' => PostStatus::PUBLISHED->value,
            'published_at' => now(),
        ]
    );
    $category = Category::factory()->create();

    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

    $this->actingAs($user)
        ->patch(route('admin.posts.draft.update', $post), [
            'title' => 'My first post',
            'excerpt' => 'My first post excerpt',
            'body' => 'My first post body',
            'thumbnail' => $thumbnail,
            'category_id' => $category->id,

        ])->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'title' => 'My first post',
        'excerpt' => 'My first post excerpt',
        'body' => 'My first post body',
        'thumbnail' => 'thumbnails/' . $thumbnail->hashName(),
        'category_id' => $category->id,
        'status' => PostStatus::DRAFT->value,
    ]);

});

it('can not update a draft post', function (): void {

    $user = User::factory()->create(
        [
            'role' => Role::MEMBER->value,
            'email_verified_at' => now(),
        ]
    );

    $post = Post::factory()->create(
        [
            'user_id' => $user->id,
            'status' => PostStatus::PUBLISHED->value,
            'published_at' => now(),
        ]
    );
    $category = Category::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.posts.draft.update', $post), [
            'title' => 'My first post',
            'slug' => 'my-first-post',
            'excerpt' => 'My first post excerpt',
            'body' => 'My first post body',
            'category_id' => $category->id,
            'published_at' => null,

        ])->assertForbidden();

    $this->assertDatabaseMissing('posts', [
        'title' => 'My first post',
        'slug' => 'my-first-post',
        'excerpt' => 'My first post excerpt',
        'body' => 'My first post body',
        'category_id' => $category->id,
        'published_at' => null,
        'status' => PostStatus::DRAFT->value,
    ]);

});
;
