<?php

namespace App\Utilities;

class RequestUtilities
{
    public static function convertCheckboxValueToBoolean($value): bool
    {
        if (isset($value)) {
            if ($value === 'on') {
                return true;
            }
        }

        return false;
    }
}
