<?php

namespace App\Http\Controllers\Admin;

use App\Events\Post\PostCreated;
use App\Events\Post\PostDeleted;
use App\Events\Post\PostPublished;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Utilities\FilterContent;
use App\Utilities\PostStatus;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(): View
    {
        return view('admin.posts.index', [
            'posts' => Post::latest()
                ->paginate(20)
        ]);
    }

    public function store(): RedirectResponse
    {
        $attributes = array_merge($this->validatePost(), [
            'user_id' => auth()->id(),
            'thumbnail' => request()->file('thumbnail')-> store('thumbnails', 'public')
        ]);

        $post = new Post($attributes);
        $post->save();

        PostCreated::dispatch($post);

        return redirect('/admin/posts')->with('success', 'Your post has been created !');
    }

    public function update(Post $post): RedirectResponse
    {
        $attributes = $this->validatePost($post);

        if($attributes['thumbnail'] ?? false){
            $attributes['thumbnail'] = request()->file('thumbnail')->store('thumbnails', 'public');
        }

        if($post->isPublished()){
            PostPublished::dispatch($post);
        }

        $post->update($attributes);

        return redirect('/admin/posts')->with('success', 'Your post has been updated!');
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'categories' => Category::all()
        ]);
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => Category::all()
        ]);
    }

    public function destroy(Post $post): RedirectResponse
    {
        if($post->thumbnail){
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();

        PostDeleted::dispatch($post);

        return redirect('/admin/posts')->with('success', 'Your post has been deleted!');
    }

    /**
     * @param Post|null $post
     * @return array
     */
    protected function validatePost(?Post $post = null): array
    {
        $post ??= new Post();
        $attributes = request()->validate([
            'title' => 'required',
            'thumbnail' => $post->exists ? ['image', 'max:1024'] : 'required|image|max:1024',
            'slug' => ['required', Rule::unique('posts', 'slug')->ignore($post)],
            'excerpt' => 'required',
            'body' => 'required',
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'published_at' => ['nullable', 'date', 'after_or_equal:today']
        ]);
        $attributes['excerpt'] = FilterContent::apply($attributes['excerpt'], ['script']);
        $attributes['body'] = FilterContent::apply($attributes['body'], ['script']);

        if(!array_key_exists('published_at', $attributes)){
            $attributes['status'] = PostStatus::PUBLISHED;
            $attributes['published_at'] = now();
        }
        else{
            $attributes['status'] = PostStatus::DRAFT;
            $attributes['published_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $attributes['published_at']);
        }

        return $attributes;
    }

}
