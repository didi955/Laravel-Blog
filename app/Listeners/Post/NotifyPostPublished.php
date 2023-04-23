<?php

namespace App\Listeners\Post;

use App\Events\Post\PostPublished;
use App\Notifications\Post\NewPostPublished;

class NotifyPostPublished
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
        $event->post->author->notify(new NewPostPublished($event->post));
        $event->post->notifySubscribers();
    }
}
