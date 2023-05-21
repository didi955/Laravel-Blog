<?php

namespace App\Listeners\Post;

use App\Events\Post\PostCreated;
use App\Notifications\Post\ScheduledPostCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyScheduledPostCreated implements ShouldQueue
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
        if (!$event->post->isScheduled()) {
            return;
        }

        $event->post->author->notify(new ScheduledPostCreated($event->post));
    }
}
