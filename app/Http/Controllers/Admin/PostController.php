<?php

namespace App\Http\Controllers\Admin;

use App\Events\Post\PostCreated;
use App\Events\Post\PostDeleted;
use App\Events\Post\PostPublished;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Jobs\PublishPost;
use App\Models\Category;
use App\Models\Post;
use App\Utilities\PostStatus;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(): View
    {
        return view('admin.posts.index', [
            'posts' => Post::latest()
                ->paginate(20),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $attributes = array_merge($this->validatePost($request), [
            'user_id'   => auth()->id(),
            'thumbnail' => request()->file('thumbnail')->store('thumbnails', 'public'),
        ]);

        $post = new Post($attributes);
        $post->save();

        $this->broadcast($post);

        PostCreated::dispatch($post);

        return redirect('/admin/posts')->with('success', 'Your post has been created !');
    }

    public function update(PostRequest $request): RedirectResponse
    {
        $attributes = $this->validatePost($request);

        $post = Post::where('slug', $request->post)->first();

        $post->update($attributes);

        $this->broadcast($post, true);

        return redirect('/admin/posts')->with('success', 'Your post has been updated!');
    }

    public function storeDraft(PostRequest $request): RedirectResponse
    {
        $attributes = array_merge($this->validatePost($request, true), [
            'user_id'   => auth()->id(),
            'thumbnail' => request()->file('thumbnail')->store('thumbnails', 'public'),
        ]);

        $post = new Post($attributes);
        $post->save();

        return redirect('/admin/posts')->with('success', 'Your post has been saved as draft!');
    }

    public function updateDraft(PostRequest $request): RedirectResponse
    {
        $attributes = $this->validatePost($request, true);

        $post = Post::where('slug', $request->post)->first();

        $post->update($attributes);

        return redirect('/admin/posts')->with('success', 'Your post has been updated!');
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'categories' => Category::all(),
        ]);
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post'       => $post,
            'categories' => Category::all(),
        ]);
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();

        PostDeleted::dispatch($post);

        return redirect('/admin/posts')->with('success', 'Your post has been deleted!');
    }

    /**
     * @param PostRequest $request
     *
     * @return array
     */
    protected function validatePost(PostRequest $request, bool $draft = false): array
    {
        $attributes = $request->validated();

        if ($attributes['thumbnail'] ?? false) {
            $attributes['thumbnail'] = request()->file('thumbnail')->store('thumbnails', 'public');
        }

        if (!array_key_exists('published_at', $attributes)) {
            if ($draft) {
                $attributes['status'] = PostStatus::DRAFT->value;
            } else {
                $attributes['status'] = PostStatus::PUBLISHED->value;
            }
            $attributes['published_at'] = now();
        } else {
            if ($draft) {
                $attributes['status'] = PostStatus::DRAFT->value;
            } else {
                $attributes['status'] = PostStatus::PENDING->value;
            }
            $attributes['published_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $attributes['published_at']);
        }

        return $attributes;
    }

    private function broadcast(Post $post, bool $wasEdit = false): void
    {
        if ($post->status === PostStatus::PUBLISHED && !$wasEdit) {
            PostPublished::dispatch($post);
        } elseif ($post->status === PostStatus::PENDING) {
            PublishPost::dispatch($post, $post->published_at, $wasEdit)->delay($post->published_at);
        }
    }
}
