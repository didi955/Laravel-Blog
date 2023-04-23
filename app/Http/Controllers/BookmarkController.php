<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        return view('bookmark.index', [
            'bookmarks' => auth()->user()->bookmarks()
        ]);
    }

    public function store(Bookmark $bookmark)
    {
        auth()->user()->bookmarks()->attach($bookmark);
        return back();
    }

    public function destroy(Bookmark $bookmark)
    {
        auth()->user()->bookmarks()->detach($bookmark);
        return back();
    }
}
