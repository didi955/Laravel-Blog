<?php

declare(strict_types=1);

namespace Tests\Unit\utilities;

use App\Utilities\Role;

it('check roles power', function (): void {
    expect(Role::ADMIN->power())->toBe(99)
        ->and(Role::MEMBER->power())
        ->toBe(10);

});

it('comparisons between multiple roles power', function (): void {
    expect(Role::ADMIN->isHigherEqualThan(Role::MEMBER))->toBeTrue()
        ->and(Role::ADMIN->isHigherThan(Role::MEMBER))
        ->toBeTrue()
        ->and(Role::ADMIN->isLowerEqualThan(Role::MEMBER))
        ->toBeFalse()
        ->and(Role::ADMIN->isLowerThan(Role::MEMBER))
        ->toBeFalse()
        ->and(Role::ADMIN->isEquals(Role::MEMBER))
        ->toBeFalse();
});
