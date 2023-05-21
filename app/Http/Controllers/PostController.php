<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Utilities\PostStatus;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        // return view with all posts that are published
        return view('posts.index', [
            'posts' => Post::where('status', PostStatus::PUBLISHED->value)->latest()->filter(
                request(['search', 'category', 'author'])
            )->paginate(6)->withQueryString(),
        ]);
    }

    public function show(Post $post): View
    {
        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
