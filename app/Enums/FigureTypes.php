<?php

declare(strict_types=1);

namespace App\Enums;

enum FigureTypes : string
{
    case Hint = 'Подсказка';
    case Step = 'ход';
    case Coins = 'монеты';
}
