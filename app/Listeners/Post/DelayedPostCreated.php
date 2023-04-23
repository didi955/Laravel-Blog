<?php

namespace App\Listeners\Post;

use App\Events\Post\PostCreated;
use App\Jobs\PublishDelayedPost;
use App\Notifications\Post\NewDelayedPostCreated;
use Illuminate\Queue\Queue;

class DelayedPostCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostCreated $event): void
    {
        if (!$event->post->is_published) {
            PublishDelayedPost::dispatch($event->post)->delay($event->post->published_at);
            $event->post->author->notify(new NewDelayedPostCreated($event->post));
        }
    }
}
