<?php

declare(strict_types=1);

namespace Tests\Feature\jobs;

use App\Jobs\PublishPost;
use App\Models\Post;
use App\Services\PostService;
use App\Utilities\PostStatus;
use Illuminate\Support\Facades\Queue;

beforeEach(function (): void {
    $this->post = Post::factory()->create(
        [
            'status' => PostStatus::PENDING->value,
            'published_at' => now()->addDay(),
        ]
    );
});


it('dispatches a job to publish a post', function (): void {

    Queue::fake();

    $postService = new PostService();
    $postService->broadcast($this->post);

    Queue::assertPushed(PublishPost::class);

});

it('publishes ', function (): void {

    (new PublishPost($this->post, $this->post->published_at))->handle();

    $this->post->refresh();

    expect($this->post->status)->toEqual(PostStatus::PUBLISHED);

});
