<?php

namespace App\Models;

use App\Enums\FrequencyEnum;
use Carbon\Carbon;
use Database\Factories\MonitorFactory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Ramsey\Collection\Collection;

/**
 * @mixin EloquentBuilder<Monitor>
 * @mixin QueryBuilder
 *
 * @property int $id
 * @property int $user_id
 * @property string $url
 * @property int $expected_http_code
 * @property FrequencyEnum $frequency
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @property Collection<MonitorAccessToken> $accessTokens
 * @property Collection<MonitorCheck> $checks
 */
final class Monitor extends Model
{
    /** @use HasFactory<MonitorFactory> */
    use HasFactory;

    // ---------------------- Properties ----------------------

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'url',
        'expected_http_code',
        'frequency',
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
            'user_id' => 'int',
            'url' => 'string',
            'expected_http_code' => 'int',
            'frequency' => FrequencyEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ---------------------- Relations ----------------------

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<MonitorAccessToken, $this>
     */
    public function accessTokens(): HasMany
    {
        return $this->hasMany(MonitorAccessToken::class);
    }

    /**
     * @return HasMany<MonitorCheck, $this>
     */
    public function checks(): HasMany
    {
        return $this->hasMany(MonitorCheck::class);
    }
}
