<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property-read Collection|Post[] $posts
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if($attributes['name'] ?? false) {
            $this->slug = $attributes['name'];
        }
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function name(): Attribute
    {
        return Attribute::make(
            set: function (string $value): string {
                $this->slug = Str::slug($value);
                return $value;
            },
        );
    }

}
