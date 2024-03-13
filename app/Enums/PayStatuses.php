<?php

declare(strict_types=1);

namespace App\Enums;

enum PayStatuses : string
{
    case CREATED = 'Создан';
    case CANCELLED = 'Отменен';
    case PAYED = 'Оплачен';
}
