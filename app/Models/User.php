<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\User\ResetPassword;
use App\Notifications\User\VerifyEmailQueued;
use App\Utilities\Role;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int         $id
 * @property string      $lastname
 * @property string      $firstname
 * @property string      $username
 * @property string      $email
 * @property Role        $role
 * @property string      $password
 * @property string|null $avatar
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property-read Collection|Bookmark[] $bookmarks
 * @property-read Collection|Post[] $posts
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use CanResetPassword;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    private const DEFAULT_AVATAR = 'default-avatar.png';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getAvatarAsset(): string
    {
        if (null === $this->attributes['avatar']) {
            return asset('images/' . self::DEFAULT_AVATAR);
        }

        return asset('storage/' . $this->attributes['avatar']);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailQueued());
    }

    /**q
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification(mixed $token): void
    {
        $url = route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ]);

        $this->notify(new ResetPassword($url));
    }

    public function hasBookmarked(Post $post): bool
    {
        return $this->bookmarks()->where('post_id', $post->id)->limit(1)->exists();
    }

    public function password(): Attribute
    {
        return Attribute::make(
            set: function (?string $value): ?string {
                if (Hash::needsRehash($value)) {
                    $value = Hash::make($value);
                }
                return $value;
            },
        );
    }

    public function email(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => trim(mb_strtolower($value)),
        );
    }

    public function role(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): Role => Role::from($value),
        );
    }
}
