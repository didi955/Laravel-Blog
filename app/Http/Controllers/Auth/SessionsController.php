<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\SessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SessionsController extends Controller
{
    public function __construct(private readonly SessionService $sessionService)
    {
    }

    public function create(): View
    {
        return view('sessions.create');
    }

    /**
     * @throws ValidationException
     */
    public function store(): RedirectResponse
    {
        $attributes = request()->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'password' => [Password::min(8)->letters()->numbers()->mixedCase()->symbols()->required(), 'max:255',],
        ]);

        $this->sessionService->login($attributes);

        return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'Welcome Back !');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->sessionService->logout($request);

        return redirect(RouteServiceProvider::HOME)->with('success', 'Goodbye !');
    }

}
