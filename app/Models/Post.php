<?php

declare(strict_types=1);

namespace App\Models;

use App\Utilities\PostStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $body
 * @property PostStatus $status
 * @property string $thumbnail
 * @property Carbon $published_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $categories
 * @property-read User $author
 * @property-read Collection|Comment[] $comments
 */
class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['category', 'author'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if($attributes['title'] ?? false) {
            $this->slug = $attributes['title'];
        }
    }

    /**
     * add filtering.
     *
     * @param  Builder $query: query builder.
     * @param  array $filters: array of filters.
     * @return void
     */
    public function scopeFilter(Builder $query, array $filters = []): void
    {
        $query->when(
            $filters['search'] ?? false,
            fn (Builder $query, string $search) => $query->where(
                fn (Builder $query) => $query
                    ->where('title', 'like', '%' . mb_strtolower($search) . '%')
                    ->orWhere('body', 'like', '%' . mb_strtolower($search) . '%')
            )
        );

        $query->when(
            $filters['category'] ?? false,
            fn (Builder $query, string $category) => $query->whereHas(
                'category',
                fn (Builder $query) => $query->where('slug', $category)
            )
        );

        $query->when(
            $filters['author'] ?? false,
            fn (Builder $query, string $author) => $query->whereHas(
                'author',
                fn (Builder $query) => $query->where('username', $author)
            )
        );
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

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): PostStatus => PostStatus::from($value),
            set: fn (PostStatus $value): string => $value->value,
        );
    }

    public function isPublished(): bool
    {
        return PostStatus::PUBLISHED === $this->status
               && $this->published_at->lte(now());
    }

    public function isScheduled(): bool
    {
        return PostStatus::PENDING === $this->status;
    }

    public function notifySubscribers(): void
    {
        // TODO: Implement notifySubscribers() method.
    }

    public function publishedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string|Carbon $value): Carbon => Carbon::parse($value),
            set: fn (mixed $value): Carbon => Carbon::parse($value),
        );
    }

    public function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => ucfirst($value),
            set: function (string $value): string {
                $this->slug = $value;
                return $value;
            },
        );
    }

    public function slug(): Attribute
    {
        return Attribute::make(
            set: function (string $value): string {
                $slug = Str::slug($value);
                while (Post::where('slug', $slug)->where('id', '!=', $this->id)
                    ->exists()) {
                    $slug = Str::slug($value) . '-' . Str::random(5);
                }
                return $slug;
            },
        );
    }
}
