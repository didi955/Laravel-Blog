<?php

namespace App\Utilities;

enum Role: string
{
    case ADMIN = 'Admin';
    case MEMBER = 'Member';

    public function power(): int
    {
        return match ($this->value) {
            'Admin'  => 99,
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
}
