<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\Post\PostPublished;
use App\Models\Post;
use App\Utilities\PostStatus;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishPost implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Post $post, public readonly Carbon $date, private readonly bool $wasEdit = false)
    {
        $this->onQueue('publishing');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (PostStatus::PENDING === $this->post->status && $this->date->eq($this->post->published_at)) {
            $this->post->update([
                'status' => PostStatus::PUBLISHED,
            ]);
            if ( ! $this->wasEdit) {
                PostPublished::dispatch($this->post, true);
            }
        }
    }
}
