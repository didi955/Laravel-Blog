<?php

declare(strict_types=1);

namespace Tests\Feature\events;

use App\Events\Post\PostPublished;
use App\Listeners\Post\NotifyPostPublished;
use App\Models\Post;
use App\Utilities\PostStatus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

it('is attached with NotifyPostPublished listener', function (): void {

    Event::fake();
    Event::assertListening(PostPublished::class, NotifyPostPublished::class);
});

it('is send a notification when a post is published', function (): void {

    Notification::fake();

    $post = Post::factory()->create([
        'published_at' => now(),
        'status' => PostStatus::PUBLISHED->value,
    ]);

    $event = new PostPublished($post);
    $listener = new NotifyPostPublished();
    $listener->handle($event);

    Notification::assertSentTo($post->author, \App\Notifications\Post\PostPublished::class);

});
