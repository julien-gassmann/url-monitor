<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\HttpCodeEnum;
use App\Enums\StatusEnum;
use App\Models\Contracts\SpatieQueryableModel;
use App\Traits\UsesSpatieQueryBuilder;
use Carbon\Carbon;
use Database\Factories\MonitorCheckFactory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin EloquentBuilder<MonitorCheck>
 * @mixin QueryBuilder
 *
 * @property int $id
 * @property int $monitor_id
 * @property HttpCodeEnum $http_code
 * @property StatusEnum $status
 * @property Carbon $checked_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Monitor $monitor
 */
final class MonitorCheck extends Model implements SpatieQueryableModel
{
    /** @use HasFactory<MonitorCheckFactory> */
    use HasFactory, UsesSpatieQueryBuilder;

    // ---------------------- Properties ----------------------

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'monitor_id',
        'http_code',
        'status',
        'checked_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'int',
            'monitor_id' => 'int',
            'http_code' => HttpCodeEnum::class,
            'status' => StatusEnum::class,
            'checked_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ---------------------- Spatie Query Builder ----------------------

    /** @return  array<int, string> */
    public static function allowedFilters(): array
    {
        return [
            'status',
            'http_code',
        ];
    }

    /** @return  array<int, string> */
    public static function allowedScopes(): array
    {
        return [];
    }

    /** @return  array<int, string> */
    public static function allowedSorts(): array
    {
        return [
            'status', '-status',
            'http_code', '-http_code',
            'checked_at', '-checked_at',
        ];
    }

    /** @return  array<int, string> */
    public static function allowedIncludes(): array
    {
        return [];
    }

    // ---------------------- Relations ----------------------

    /**
     * @return BelongsTo<Monitor, $this>
     */
    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
}
