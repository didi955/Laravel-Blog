<?php

declare(strict_types=1);

namespace App\Utilities;

enum Role: string
{
    case ADMIN = 'Admin';
    case MEMBER = 'Member';

    public function power(): int
    {
        return match ($this->value) {
            'Admin' => 99,
            'Member' => 10,
        };
    }

    public function isHigherEqualThan(Role $otherRole): bool
    {
        return $this->power() >= $otherRole->power();
    }

    public function isHigherThan(Role $otherRole): bool
    {
        return $this->power() > $otherRole->power();
    }

    public function isLowerEqualThan(Role $otherRole): bool
    {
        return $this->power() <= $otherRole->power();
    }

    public function isLowerThan(Role $otherRole): bool
    {
        return $this->power() < $otherRole->power();
    }

    public function isEquals(Role $otherRole): bool
    {
        return $this->power() === $otherRole->power();
    }
}
