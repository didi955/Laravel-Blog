<?php

declare(strict_types=1);

namespace App\Utilities;

use App\Services\PostService;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

test('Process status function is valid', function (): void {
    $attributes = [
        'title' => 'Test Title',
        'content' => 'Test Content',
        'published_at' => Carbon::now()->addDay()->format('Y-m-d\TH:i'),
    ];

    $postService = new PostService();

    $attributes = $postService->processStatus($attributes);

    expect($attributes['status'])->toBe(PostStatus::PENDING->value)
        ->and($attributes['published_at'])
        ->toBeInstanceOf(Carbon::class);

    $attributes = [
        'title' => 'Test Title',
        'content' => 'Test Content',
    ];

    $attributes = $postService->processStatus($attributes);

    expect($attributes['status'])->toBe(PostStatus::PUBLISHED->value)
        ->and($attributes['published_at'])
        ->toBeInstanceOf(Carbon::class);



});

it('ProcessStatus method throws an InvalidFormatException', function (): void {
    $attributes = [
        'published_at' => null,
    ];

    $postService = new PostService();

    $postService->processStatus($attributes);

})->throws(InvalidFormatException::class);
