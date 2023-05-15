<?php

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
use function Illuminate\Tests\Integration\Routing\fail;

class PublishPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Post $post, public readonly Carbon $date, private readonly bool $wasEdit = false)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->post->status === PostStatus::PENDING && $this->date->eq($this->post->published_at)){
            $this->post->update([
                'status' => PostStatus::PUBLISHED,
            ]);
            if(!$this->wasEdit)
                PostPublished::dispatch($this->post, true);
        }
    }
}
