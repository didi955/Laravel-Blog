<?php

namespace App\Listeners\Post;

use App\Events\Post\PostDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteDelayedJob
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
    public function handle(PostDeleted $event): void
    {
        // Delete a dispatched job from the queue
    }
}
