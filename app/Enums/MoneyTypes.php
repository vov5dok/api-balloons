<?php

declare(strict_types=1);

namespace App\Enums;

enum MoneyTypes : string
{
    case MONEY = 'деньги';
    case COINS = 'монеты';
}
