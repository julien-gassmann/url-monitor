<?php

namespace App\Enums;

enum StatusEnum: string
{
    case UP = 'up';
    case DOWN = 'down';
    case UNREACHABLE = 'unreachable';
}
