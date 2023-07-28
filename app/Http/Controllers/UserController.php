<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(): View
    {
        return view('profile.index');
    }

    public function update(UserRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        try {

            $this->userService->update($attributes);

        } catch (\Exception) {
            return back()->with(
                'error',
                'An error occurred while updating your profile'
            );
        }

        return back()->with('success', 'Profile updated successfully');
    }
}
