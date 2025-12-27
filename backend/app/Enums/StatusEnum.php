<?php

namespace App\Enums;

enum StatusEnum: string
{
    case UP = 'up';
    case DOWN = 'down';
    case UNREACHABLE = 'unreachable';

    public function label(): string
    {
        return $this->name;
    }
}
