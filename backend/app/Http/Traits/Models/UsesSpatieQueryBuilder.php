<?php

declare(strict_types=1);

namespace App\Http\Traits\Models;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Trait providing helper methods to build a query using Spatie\QueryBuilder
 * based on allowed filters, sorts, and includes declared in the model.
 */
trait UsesSpatieQueryBuilder
{
    /** @return  array<int, string> */
    public static function getAllowedAttributesForFilter(): array
    {
        return [];
    }

    /** @return  array<int, string> */
    public static function getAllowedAttributesForSort(): array
    {
        return [];
    }

    /** @return  array<int, string> */
    public static function getAllowedScopesForFilter(): array
    {
        return [];
    }

    /** @return  array<int, string> */
    public static function getAllowedRelationsForLoading(): array
    {
        return [];
    }

    /**
     * Determines the number of items per page based on the request.
     * Returns the total count if the value is -1 (meaning "all").
     */
    public static function perPage(FormRequest $request, ?int $max = null): int
    {
        return ($perPage = $request->integer('per_page', -1)) === -1
            ? ($max ?? static::count())
            : $perPage;
    }

    /**
     * Builds a query builder instance with filters, sorts, and includes
     * according to the model's allowed properties.
     *
     * @return QueryBuilder<static>
     */
    public static function whereMatchesRequest(): QueryBuilder
    {
        return QueryBuilder::for(static::class)
            ->allowedFilters([
                // Map allowed scopes to nullable Spatie scope filters
                // Applied even when the value is null
                ...array_map(
                    fn (string $scope): AllowedFilter => AllowedFilter::scope($scope)->nullable(),
                    static::getAllowedScopesForFilter()
                ),
                // Map allowed attributes to exact-match filters
                // Applied even when the value is null
                ...array_map(
                    fn (string $attribute): AllowedFilter => AllowedFilter::exact($attribute)->nullable(),
                    static::getAllowedAttributesForFilter()
                ),
            ])
            ->allowedSorts(static::getAllowedAttributesForSort())
            ->allowedIncludes(static::getAllowedRelationsForLoading());
    }
}
