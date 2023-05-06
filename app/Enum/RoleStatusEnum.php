<?php

namespace App\Enum;

enum RoleStatusEnum :int
{
    case ADMIN = 1;
    case MARKETING = 2;

    public function toString()
    {
        return match($this) {
            RoleStatusEnum::ADMIN => 'Admin',
            RoleStatusEnum::MARKETING => 'Marketing',
        };
    }
}
