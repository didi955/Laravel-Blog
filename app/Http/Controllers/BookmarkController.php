<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
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
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ];

        try {
            auth()->user()->bookmarks()->create($attributes);
        }
        catch (\Exception $e) {
            return back()->with('error', 'Post already bookmarked');
        }
        return back()->with('success', 'Post bookmarked');
    }

    public function destroy(Post $post): RedirectResponse
    {
        try {
            auth()->user()->bookmarks()->where('post_id', $post->id)->first()->delete();
        }
        catch (\Exception $e) {
            return back()->with('error', 'Bookmark not found');
        }
        return back()->with('success', 'Bookmark deleted');
    }
}
