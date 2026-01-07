<?php

declare(strict_types=1);

namespace App\Models\Contracts;

interface SpatieQueryableModel
{
    /** @return  array<int, string> */
    public static function allowedFilters(): array;

    /** @return  array<int, string> */
    public static function allowedScopes(): array;

    /** @return  array<int, string> */
    public static function allowedSorts(): array;

    /** @return  array<int, string> */
    public static function allowedIncludes(): array;
}
