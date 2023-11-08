<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\Post\PostPublished;
use App\Models\Post;
use App\Utilities\PostStatus;
use Illuminate\Console\Command;

class PublishPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled posts that are due.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Post::where('published_at', '<=', now())
            ->where('status', PostStatus::PENDING)
            ->each(function (Post $post) {
                $post->update([
                    'status' => PostStatus::PUBLISHED,
                ]);
                PostPublished::dispatch($post, true);
            });
    }
}
