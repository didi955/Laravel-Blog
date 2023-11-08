<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Post\PostCreated;
use App\Events\Post\PostDeleted;
use App\Events\Post\PostPublished;
use App\Models\Post;
use App\Utilities\PostStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function delete(Post $post): void
    {
        $this->deleteThumbnail($post);
        $post->delete();
        PostDeleted::dispatch($post);
    }

    public function create(array $attributes): void
    {
        $post = new Post($attributes);

        $post->thumbnail = $this->storeThumbnail();
        $post->save();

        $this->broadcast($post);
        PostCreated::dispatch($post);
    }

    public function update(string $slug, array $attributes): void
    {
        $post = Post::where('slug', $slug)->first();

        if($attributes['thumbnail'] ?? false) {
            $this->deleteThumbnail($post);
            $attributes['thumbnail'] = $this->storeThumbnail();
        }

        $post->update($attributes);

        $this->broadcast($post);

    }

    public function processStatus(array $attributes, bool $draft = false): array
    {
        if ( ! array_key_exists('published_at', $attributes)) {
            if ($draft) {
                $attributes['status'] = PostStatus::DRAFT->value;
            } else {
                $attributes['status'] = PostStatus::PUBLISHED->value;
            }
            $attributes['published_at'] = now();
        } else {
            if ($draft) {
                $attributes['status'] = PostStatus::DRAFT->value;
            } else {
                $attributes['status'] = PostStatus::PENDING->value;
            }
            $attributes['published_at'] = Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $attributes['published_at']
            );

        }

        return $attributes;
    }

    public function broadcast(Post $post): void
    {
        if (PostStatus::PUBLISHED === $post->status && $post->published_at->lte(now())) {
            PostPublished::dispatch($post);
        }
    }
    private function storeThumbnail(): false|string
    {
        return request()->file('thumbnail')
            ->store('thumbnails', 'public');

    }

    private function deleteThumbnail(Post $post): void
    {
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }
    }
}
