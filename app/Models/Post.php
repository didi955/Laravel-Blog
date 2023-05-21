<?php

namespace App\Models;

use App\Utilities\PostStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 *
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $body
 * @property PostStatus $status
 * @property string $thumbnail
 * @property Carbon $published_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Category $categories
 * @property-read User $author
 * @property-read Collection|Comment[] $comments
 *
 */

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['category', 'author'];

    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where(fn($query) =>
                $query
                ->where('title', 'like', '%' . strtolower($search) . '%')
                ->orWhere('body', 'like', '%' . strtolower($search) . '%')
        ));

        $query->when($filters['category'] ?? false, fn($query, $category) =>
            $query->whereHas('category', fn($query) =>
                $query->where('slug', $category)
        ));

        $query->when($filters['author'] ?? false, fn($query, $author) =>
            $query->whereHas('author', fn($query) =>
                $query->where('username', $author)
        ));

    }

    public function getPublishedAtAttribute($value): Carbon
    {
        return Carbon::parse($value);
    }

    public function setPublishedAtAttribute($value): void
    {
        $this->attributes['published_at'] = Carbon::parse($value);
    }

    public function setTitleAttribute($value): void
    {
        $this->attributes['title'] = $value;
        $slug = Str::slug($value);
        while (Post::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = Str::slug($value) . '-' . Str::random(5);
        }
        $this->attributes['slug'] = $slug;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getStatusAttribute(string $value): PostStatus
    {
        return PostStatus::from($value);
    }

    public function isPublished(): bool
    {
        return $this->status == PostStatus::PUBLISHED
            && $this->published_at->lte(now());
    }

    public function isScheduled(): bool
    {
        return $this->status == PostStatus::PENDING;
    }

    public function notifySubscribers()
    {
        // TODO: Implement notifySubscribers() method.
    }


}



