<?php

use Tests\Queries\ActorQueries;
use Tests\Queries\DatabaseSetupQueries;
use Tests\Queries\MonitorQueries;

return [

    /*
    |--------------------------------------------------------------------------
    | Strict Mode
    |--------------------------------------------------------------------------
    |
    | Control how the test suite reacts to errors.
    |
    | - configuration: invalid or missing scenario definitions
    | - resolution: valid definitions that fail at runtime
    |
    | true  → fail the test suite
    | false → skip the test instead
    |
    */

    'strict_mode' => [
        'configuration' => env('PEST_SCENARIOS_STRICT_CONFIGURATION', true),
        'resolution' => env('PEST_SCENARIOS_STRICT_RESOLUTION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Scenario Resolvers
    |--------------------------------------------------------------------------
    |
    | Resolvers are named definitions used by scenarios and contexts to
    | dynamically resolve data at runtime.
    |
    | They allow scenarios to stay declarative while centralizing logic such as:
    | - resolving acting users (actors)
    | - preparing database state (database_setups)
    | - asserting API response shapes (json_structures)
    | - querying domain objects (queries)
    |
    */

    'resolvers' => [
        'actors' => [
            'user' => ActorQueries::user(...),
            'other' => ActorQueries::other(...),
            'sanctum' => ActorQueries::sanctum(...),
            'guest' => fn (): null => null,
        ],

        'database_setups' => [
            'create_monitor' => DatabaseSetupQueries::createMonitor(...),
            'create_other_monitor' => DatabaseSetupQueries::createOtherMonitor(...),
            'create_checks' => DatabaseSetupQueries::createChecks(...),
            'create_other_checks' => DatabaseSetupQueries::createOtherChecks(...),
            'create_token' => DatabaseSetupQueries::createAccessToken(...),
            'create_used_token' => DatabaseSetupQueries::createUsedAccessToken(...),
            'create_refreshed_token' => DatabaseSetupQueries::createRefreshedAccessToken(...),
            'create_expired_token' => DatabaseSetupQueries::createExpiredAccessToken(...),
            'create_many_monitors' => DatabaseSetupQueries::createManyMonitors(...),
            'create_many_tokens' => DatabaseSetupQueries::createManyAccessTokens(...),
        ],

        'json_structures' => [
            'resource' => ['data'],
            'pagination' => [
                'data',
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => ['*' => ['url', 'label', 'active']],
                ],
            ],
            'token' => ['token'],
            'verify_token_result' => [
                'is_valid',
                'reason',
                'monitor_uuid',
                'bearer' => ['token', 'expires_at'],
            ],
            'message' => ['message'],
            'none' => null, // Explicitly assert no JSON structure
        ],

        'queries' => [
            'monitor' => MonitorQueries::getFirst(...),
            'other_monitor' => MonitorQueries::getOther(...),
            'checks' => MonitorQueries::getFirstChecks(...),
            'other_checks' => MonitorQueries::getOtherChecks(...),
        ],
    ],
];
