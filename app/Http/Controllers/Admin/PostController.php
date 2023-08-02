<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\PostService;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService)
    {
    }

    public function index(): View
    {
        return view('admin.posts.index', [
            'posts' => Post::latest()
                ->paginate(20),
        ]);
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
            'post' => $post,
            'categories' => Category::all(),
        ]);
    }

    public function storeDraft(PostRequest $request): RedirectResponse
    {
        return $this->store($request, true);
    }

    public function store(PostRequest $request, bool $draft=false): RedirectResponse
    {
        $attributes = array_merge($this->validatePost($request, $draft), [
            'user_id' => auth()->id(),
        ]);

        $this->postService->create($attributes);


        return redirect(route('admin.posts.index'))->with(
            'success',
            'Your post has been created !'
        );
    }

    public function updateDraft(PostRequest $request): RedirectResponse
    {
        $attributes = $this->validatePost($request, true);

        $this->postService->update($request->post, $attributes);

        return redirect(route('admin.posts.index'))->with(
            'success',
            'Your post has been updated !'
        );
    }

    public function update(PostRequest $request): RedirectResponse
    {
        $attributes = $this->validatePost($request);

        $this->postService->update($request->post, $attributes);

        return redirect(route('admin.posts.index'))->with(
            'success',
            'Your post has been updated !'
        );
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->postService->delete($post);

        return redirect(route('admin.posts.index'))->with(
            'success',
            'Your post has been deleted !'
        );
    }

    protected function validatePost(
        PostRequest $request,
        bool $draft = false
    ): array {
        $attributes = $request->validated();

        try {
            return $this->postService->processStatus($attributes, $draft);
        } catch (InvalidFormatException) {
            throw ValidationException::withMessages([
                'published_at' => 'The published date is invalid.',
            ]);
        }
    }
}
