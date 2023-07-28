<?php

declare(strict_types=1);

namespace Tests\Feature\events;

use App\Events\Post\PostCreated;
use App\Listeners\Post\NotifyScheduledPostCreated;
use App\Models\Post;
use App\Notifications\Post\ScheduledPostCreated;
use App\Utilities\PostStatus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

it('is attached with NotifyScheduledPostCreated listener', function (): void {

    Event::fake();
    Event::assertListening(PostCreated::class, NotifyScheduledPostCreated::class);
});

it('is send a notification when a scheduled post is created', function (): void {

    Notification::fake();

    $post = Post::factory()->create([
        'published_at' => now()->addDay(),
        'status' => PostStatus::PENDING->value,
    ]);

    $event = new PostCreated($post);
    $listener = new NotifyScheduledPostCreated();
    $listener->handle($event);

    Notification::assertSentTo($post->author, ScheduledPostCreated::class);

    // IT DOESN'T SEND A NOTIFICATION IF THE POST IS NOT SCHEDULED

    $post = Post::factory()->create([
        'published_at' => now()->addDay(),
        'status' => PostStatus::PUBLISHED->value,
    ]);

    $event = new PostCreated($post);
    $listener = new NotifyScheduledPostCreated();
    $listener->handle($event);

    Notification::assertNotSentTo($post->author, ScheduledPostCreated::class);

});
