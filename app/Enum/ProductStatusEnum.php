<?php

namespace App\Enum;

enum ProductStatusEnum :int
{
    case INSTOCK = 0;
    case OUTSTOCK = 1;

    public function toString()
    {
        return match($this) {
            ProductStatusEnum::INSTOCK => 'available',
            ProductStatusEnum::OUTSTOCK => 'out of stock',
        };
    }
}
