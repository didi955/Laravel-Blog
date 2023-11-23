<?php

declare(strict_types=1);

namespace Tests\Feature\jobs;

use App\Console\Commands\PublishPosts;
use App\Models\Post;
use App\Utilities\PostStatus;

beforeEach(function (): void {
    $this->post = Post::factory()->create(
        [
            'status' => PostStatus::PENDING->value,
            'published_at' => now(),
        ]
    );
});


it('publishes ', function (): void {

    $this->artisan(PublishPosts::class);

    $this->post->refresh();

    expect($this->post->status)->toEqual(PostStatus::PUBLISHED);

});
