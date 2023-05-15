<?php

namespace App\Listeners\Post;

use App\Events\Post\PostPublished;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyPostPublished implements ShouldQueue
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
    public function handle(PostPublished $event): void
    {
        $event->post->author->notify(new \App\Notifications\Post\PostPublished($event->post, $event->wasDelayed));
        $event->post->notifySubscribers();
    }
}
