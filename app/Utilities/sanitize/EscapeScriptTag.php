<?php

namespace App\Utilities\sanitize;

use Elegant\Sanitizer\Contracts\Filter;

class EscapeScriptTag implements Filter
{
    public function apply($value, array $options = [])
    {
        return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
    }
}
