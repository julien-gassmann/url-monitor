<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Ramsey\Collection\Collection;

/**
 * @mixin EloquentBuilder<User>
 * @mixin QueryBuilder
 *
 * @property int $id
 * @property string $email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<Monitor> $monitors
 * @property Collection<MonitorAccessToken> $monitorAccessTokens
 * @property Collection<MonitorCheck> $monitorChecks
 */
final class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // ---------------------- Properties ----------------------

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
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
            'email' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ---------------------- Relations ----------------------

    /**
     * @return HasMany<Monitor, $this>
     */
    public function monitors(): HasMany
    {
        return $this->hasMany(Monitor::class);
    }

    /**
     * @return HasManyThrough<MonitorAccessToken, Monitor, $this>
     */
    public function monitorAccessTokens(): HasManyThrough
    {
        return $this->hasManyThrough(MonitorAccessToken::class, Monitor::class);
    }

    /**
     * @return HasManyThrough<MonitorCheck, Monitor, $this>
     */
    public function monitorChecks(): HasManyThrough
    {
        return $this->hasManyThrough(MonitorCheck::class, Monitor::class);
    }
}
