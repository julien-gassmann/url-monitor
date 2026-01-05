<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\MonitorAccessTokenFactory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin EloquentBuilder<MonitorAccessToken>
 * @mixin QueryBuilder
 *
 * @property int $id
 * @property int $monitor_id
 * @property string $token_hash
 * @property Carbon $expires_at
 * @property ?Carbon $used_at
 * @property ?Carbon $refreshed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Monitor $monitor
 */
final class MonitorAccessToken extends Model
{
    /** @use HasFactory<MonitorAccessTokenFactory> */
    use HasFactory;

    // ---------------------- Properties ----------------------

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'monitor_id',
        'token_hash',
        'expires_at',
        'used_at',
        'refreshed_at',
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
            'token_hash' => 'string',
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
            'refreshed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
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
