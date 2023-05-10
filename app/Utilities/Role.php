<?php

namespace App\Utilities;

enum Role : string
{

    case ADMIN = 'Admin';
    case EDITOR = 'Editor';
    case READER = 'Reader';

    public function power(): int
    {
        return match($this->value){
            'Admin' => 99,
            'Editor' => 50,
            'Reader' => 10,
        };
    }

    public function isHigherThan(Role $otherRole): bool
    {
        return $this->power() > $otherRole->power();
    }

}
