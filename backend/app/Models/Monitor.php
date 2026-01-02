<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FrequencyEnum;
use App\Enums\HttpCodeEnum;
use Carbon\Carbon;
use Database\Factories\MonitorFactory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Ramsey\Collection\Collection;

/**
 * @mixin EloquentBuilder<Monitor>
 * @mixin QueryBuilder
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $url
 * @property HttpCodeEnum $expected_http_code
 * @property FrequencyEnum $frequency
 * @property Carbon $next_check_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @property Collection<MonitorAccessToken> $accessTokens
 * @property Collection<MonitorCheck> $checks
 *
 * @method static EloquentBuilder<Monitor> dueForCheck()
 */
final class Monitor extends Model
{
    /** @use HasFactory<MonitorFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        Monitor::creating(function (self $monitor): void {
            $monitor->uuid ??= (string) Str::uuid();
        });
    }

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
        'next_check_at',
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
            'uuid' => 'string',
            'user_id' => 'int',
            'url' => 'string',
            'expected_http_code' => HttpCodeEnum::class,
            'frequency' => FrequencyEnum::class,
            'next_check_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ---------------------- Scopes ----------------------

    /**
     * Scope to retrieve monitors that should be checked
     *
     * @noinspection PhpUnused
     *
     * @param  EloquentBuilder<Monitor>  $query
     * @return EloquentBuilder<Monitor>
     */
    public function scopeDueForCheck(EloquentBuilder $query): EloquentBuilder
    {
        /** @var EloquentBuilder<Monitor> $query */
        $query = $query->whereNotNull('next_check_at')
            ->where('next_check_at', '<=', now());

        return $query;
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
