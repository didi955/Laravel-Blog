<?php

declare(strict_types=1);

namespace Tests\Unit\utilities;

use App\Utilities\RequestUtilities;

it('converts a checkbox value to a boolean', function (): void {
    expect(RequestUtilities::convertCheckboxValueToBoolean('on'))->toBeTrue()
        ->and(RequestUtilities::convertCheckboxValueToBoolean('off'))
        ->toBeFalse()
        ->and(RequestUtilities::convertCheckboxValueToBoolean(null))
        ->toBeFalse();
});
