<?php

namespace App\Utilities;

class RequestUtilities
{
    public static function convertCheckboxValueToBoolean(
        string|null $value
    ): bool {
        if (isset($value)) {
            if ($value === 'on') {
                return true;
            }
        }

        return false;
    }
}
