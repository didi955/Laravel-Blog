<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SessionsController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\PostCommentsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::middleware(['guest'])->group(function () {
    // Registration & Login
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);

    // Forgot & Reset Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->middleware('guest')->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'index'])->name('profile');
    Route::patch('/profile', [UserController::class, 'update']);
    Route::post('/logout', [SessionsController::class, 'destroy'])->name('logout');
    Route::post('/posts/{post:slug}/comments', [PostCommentsController::class, 'store']);
    Route::get('/my-bookmarks', [BookmarkController::class, 'index'])->name('my-bookmarks');
    Route::post('/bookmarks/{post:slug}', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{post:slug}', [BookmarkController::class, 'destroy']);

    // Email Verification
    Route::get('/email/verify', [EmailVerificationController::class, 'create'])->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

Route::middleware(['auth', 'can:admin', 'verified'])->group(function () {
    Route::get('/admin/posts', [Admin\PostController::class, 'index'])->name('admin.posts.index');
    Route::post('/admin/posts', [Admin\PostController::class, 'store']);
    Route::get('/admin/posts/create', [Admin\PostController::class, 'create'])->name('admin.posts.create');
    Route::get('/admin/posts/{post}/edit', [Admin\PostController::class, 'edit'])->name('admin.posts.create');
    Route::patch('/admin/posts/{post}', [Admin\PostController::class, 'update']);
    Route::delete('/admin/posts/{post}', [Admin\PostController::class, 'destroy']);
});




