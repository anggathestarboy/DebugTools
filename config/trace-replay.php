<?php

return [
    'enabled'          => env('TRACE_REPLAY_ENABLED', true),
    'sample_rate'      => env('TRACE_REPLAY_SAMPLE_RATE', 1.0), // 0.1 = 10% of requests
    'project_id'       => env('TRACE_REPLAY_PROJECT_ID', null),
    'mask_fields'      => ['password', 'password_confirmation', 'token', 'api_key',
                           'authorization', 'secret', 'credit_card', 'cvv', 'ssn', 'private_key'],
    'track_db_queries' => env('TRACE_REPLAY_TRACK_DB', true),
    'middleware'        => ['web', 'auth'],   // add 'can:view-trace-replay' for production
    'route_prefix'      => env('TRACE_REPLAY_ROUTE_PREFIX', 'trace-replay'),
    'api' => [
        'token'      => env('TRACE_REPLAY_API_TOKEN'), // API disabled until set
        'middleware' => ['api'],
        'route_prefix' => env('TRACE_REPLAY_API_ROUTE_PREFIX', 'api/trace-replay'),
        'max_steps' => env('TRACE_REPLAY_API_MAX_STEPS', 500),
    ],
    'allowed_ips'      => array_filter(explode(',', env('TRACE_REPLAY_ALLOWED_IPS', ''))),
    'queue' => [
        'enabled'    => env('TRACE_REPLAY_QUEUE_ENABLED', false),
        'connection' => env('TRACE_REPLAY_QUEUE_CONNECTION', env('QUEUE_CONNECTION', 'sync')),
        'queue'      => env('TRACE_REPLAY_QUEUE_NAME', 'default'),
    ],
    'replay' => [
        'default_base_url' => env('TRACE_REPLAY_REPLAY_URL', env('APP_URL')),
        'timeout'          => env('TRACE_REPLAY_REPLAY_TIMEOUT', 30),
        'allow_mutating_methods' => env('TRACE_REPLAY_REPLAY_MUTATING', false),
        'allowed_hosts' => array_filter(explode(',', env('TRACE_REPLAY_REPLAY_ALLOWED_HOSTS', ''))),
    ],
    'retention_days' => env('TRACE_REPLAY_RETENTION_DAYS', 30),
    'notifications' => [
        'on_failure'  => env('TRACE_REPLAY_NOTIFY_ON_FAILURE', false),
        'channels'    => ['mail'],
        'mail'        => ['to' => env('TRACE_REPLAY_NOTIFY_EMAIL')],
        'slack'       => ['webhook_url' => env('TRACE_REPLAY_SLACK_WEBHOOK')],
    ],
    'ai' => [
        'driver'  => env('TRACE_REPLAY_AI_DRIVER', 'openai'), // openai | anthropic | ollama
        'api_key' => env('TRACE_REPLAY_AI_KEY'),
        'model'   => env('TRACE_REPLAY_AI_MODEL', 'gpt-4o'),
        'base_url' => env('TRACE_REPLAY_AI_BASE_URL'),
    ],
    'auto_trace' => [
        'jobs'     => env('TRACE_REPLAY_AUTO_TRACE_JOBS', true),
        'commands' => env('TRACE_REPLAY_AUTO_TRACE_COMMANDS', false),
        'livewire' => env('TRACE_REPLAY_AUTO_TRACE_LIVEWIRE', true),
        'capture_job_payload' => env('TRACE_REPLAY_CAPTURE_JOB_PAYLOAD', false),
        'exclude_commands' => ['queue:work', 'queue:listen', 'horizon', 'schedule:run',
                               'schedule:work', 'trace-replay:prune', 'trace-replay:export'],
    ],
];
