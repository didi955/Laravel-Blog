<?php

declare(strict_types=1);

namespace Tests\Unit\utilities\sanitize;

use App\Utilities\sanitize\EscapeScriptTag;
use App\Utilities\sanitize\EscapeStyleTag;

it('escapes script tags', function (): void {
    $filter = new EscapeScriptTag();

    $value = '<script>alert("hello");</script>';

    $result = $filter->apply($value);

    expect($result)->toBe('');
});

it('escape style tags', function (): void {
    $filter = new EscapeStyleTag();

    $value = '<style>body { background-color: red; }</style>';

    $result = $filter->apply($value);

    expect($result)->toBe('');
});
