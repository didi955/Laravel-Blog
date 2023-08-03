<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Utilities\Role;
use Illuminate\Auth\Access\Response;

readonly class PostPolicy
{
    public function view(?User $user, Post $post): Response
    {
        if( ! $post->isPublished() && Role::ADMIN !== $user?->role) {
            return Response::denyAsNotFound();
        }
        return Response::allow();
    }
}
