<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BookmarkController extends Controller
{
    public function index(): View
    {
        return view('profile.bookmark.index', [
            'bookmarks' => auth()->user()->bookmarks()->paginate(20),
        ]);
    }

    public function store(Post $post): RedirectResponse
    {
        $attributes = [
            'post_id' => $post->id,
        ];

        if(auth()->user()->bookmarks()->where($attributes)->exists()) {
            return back()->with('error', 'Post already bookmarked');
        }

        auth()->user()->bookmarks()->create($attributes);

        return back()->with('success', 'Post bookmarked');
    }

    public function destroy(Post $post): RedirectResponse
    {
        try {
            auth()->user()->bookmarks()->where('post_id', $post->id)->first()
                ->delete();
        } catch (\Exception) {
            return back()->with('error', 'Bookmark not found');
        }

        return back()->with('success', 'Bookmark deleted');
    }
}
