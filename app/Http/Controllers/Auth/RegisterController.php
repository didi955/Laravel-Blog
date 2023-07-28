<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Services\RegisterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(private readonly RegisterService $registerService)
    {
    }

    public function create(): View
    {
        return view('register.create');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        $this->registerService->register($attributes);

        return redirect(RouteServiceProvider::HOME)->with('success', 'Your account has been created, please verify your email address');
    }
}
