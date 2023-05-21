<?php

namespace App\Utilities\sanitize;

use Elegant\Sanitizer\Contracts\Filter;

class EscapeStyleTag implements Filter
{
    public function apply($value, array $options = [])
    {
        return preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $value);
    }
}
