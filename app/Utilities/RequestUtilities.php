<?php

declare(strict_types=1);

namespace App\Utilities;

class RequestUtilities
{
    public static function convertCheckboxValueToBoolean(
        ?string $value
    ): bool {
        return ($value ?? false) === 'on';
    }
}
