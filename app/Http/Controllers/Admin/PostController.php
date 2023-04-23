<?php

namespace App\Http\Controllers\Admin;

use App\Events\Post\PostCreated;
use App\Events\Post\PostDeleted;
use App\Events\Post\PostPublished;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Utilities\FilterContent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', [
            'posts' => Post::latest()
                ->paginate(20)
        ]);
    }

    public function store()
    {
        $attributes = array_merge($this->validatePost(), [
            'user_id' => auth()->id(),
            'thumbnail' => request()->file('thumbnail')-> store('thumbnails', 'public')
        ]);

        if(!array_key_exists('published_at', $attributes)){
            $attributes['is_published'] = true;
            $attributes['published_at'] = now();
        }
        else{
            $attributes['is_published'] = false;
            $attributes['published_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $attributes['published_at']);
        }

        $post = new Post($attributes);
        $post->save();

        event(new PostCreated($post));

        $this->triggerPublish($post);

        return redirect('/admin/posts')->with('success', 'Your post has been created !');
    }

    public function update(Post $post)
    {
        $attributes = $this->validatePost($post);

        if($attributes['thumbnail'] ?? false){
            $attributes['thumbnail'] = request()->file('thumbnail')->store('thumbnails', 'public');
        }

        if(!array_key_exists('published_at', $attributes)){
            $attributes['is_published'] = true;
            $attributes['published_at'] = now();
        }
        else{
            $attributes['is_published'] = false;
            $attributes['published_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $attributes['published_at']);
        }

        if($post->is_published !== $attributes['is_published']){
            $this->triggerPublish($post);
        }

        $post->update($attributes);

        return redirect('/admin/posts')->with('success', 'Your post has been updated!');
    }

    public function create(){
        return view('admin.posts.create', [
            'categories' => Category::all()
        ]);
    }

    public function edit(Post $post){
        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => Category::all()
        ]);
    }

    public function destroy(Post $post)
    {
        if($post->thumbnail){
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();

        event(new PostDeleted($post));

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
        return $attributes;
    }

    private function triggerPublish(Post $post){
        if($post->is_published){
            event(new PostPublished($post));
        }
    }
}
