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
        return view('bookmark.index', [
            'bookmarks' => auth()->user()->bookmarks()
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
            return back()->with('error', 'Bookmark already exists');
        }
        return back()->with('success', 'Post bookmarked');
    }

    public function destroy(Post $post): RedirectResponse
    {
        try {
            Bookmark::findOrFail([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ])->delete();
        }
        catch (\Exception $e) {
            return back()->with('error', 'Bookmark not found');
        }
        return back()->with('success', 'Bookmark deleted');
    }
}
