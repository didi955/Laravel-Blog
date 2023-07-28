<?php

declare(strict_types=1);

namespace App\Utilities\sanitize;

use Elegant\Sanitizer\Contracts\Filter;

class EscapeScriptTag implements Filter
{
    public function apply(mixed $value, array $options = []): string|array|null
    {
        return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
    }
}
