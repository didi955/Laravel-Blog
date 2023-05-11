<?php

namespace App\Models;

use App\Notifications\User\ResetPassword;
use App\Notifications\User\VerifyEmailQueued;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

/**
 *
 * @property int $id
 * @property string $lastname
 * @property string $firstname
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $avatar
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bookmark[] $bookmarks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 *
 */

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

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
    ];

    public function setPasswordAttribute($value): void
    {
        if(Hash::needsRehash($value) ) {
            $value = Hash::make($value);
        }
        $this->attributes['password'] = $value;
    }

    public function setEmailAttribute($email): void
    {
        $this->attributes['email'] = trim(strtolower($email));
    }

    public function getAvatarAsset(): string
    {
        if($this->attributes['avatar'] === null){
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
    public function sendPasswordResetNotification($token): void
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
}
