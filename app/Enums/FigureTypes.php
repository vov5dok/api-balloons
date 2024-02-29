<?php

declare(strict_types=1);

namespace App\Enums;

enum FigureTypes : string
{
    case Hint = 'подсказка';
    case Step = 'ход';
    case Coins = 'монеты';
    case Time = 'время';
}
