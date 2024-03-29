<?php

declare(strict_types=1);

namespace App\Listeners\Post;

use App\Events\Post\PostPublished;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyPostPublished implements ShouldQueue
{
    public string $queue = 'listeners';

    /**
     * Create the event listener.
     */
    public function __construct()
    {

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
