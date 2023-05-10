<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmark extends Model
{

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @throws \Exception
     */
    public static function findOrFail(array $attributes): Bookmark
    {
        $bookmark = \DB::table('bookmarks')
            ->where('user_id', $attributes['user_id'])
            ->where('post_id', $attributes['post_id'])
            ->first();
        if (!$bookmark) {
            throw new \Exception('Bookmark not found');
        }
        return $bookmark;
    }
}
