<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Scout Engine
    |--------------------------------------------------------------------------
    |
    | This option controls the default search connection that gets used while
    | using Laravel Scout. This connection is used when syncing all models
    | to the search service. You should adjust this based on your needs.
    |
    */

    'driver' => env('SCOUT_DRIVER', 'typesense'),

    /*
    |--------------------------------------------------------------------------
    | Index Prefix
    |--------------------------------------------------------------------------
    |
    | Here you may specify a prefix that will be applied to all Scout index
    | names that are registered by default by Scout. This prefix may be
    | useful if you have multiple "tenants" or applications sharing the same
    | search infrastructure.
    |
    */

    'prefix' => env('SCOUT_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Queue Data Syncing
    |--------------------------------------------------------------------------
    |
    | This option allows you to control if the operations that sync your data
    | with your search servers are dispatched to the background queue or not.
    | This may lead to slightly faster indexing but can increase system load.
    |
    */

    'queue' => env('SCOUT_QUEUE', false),

    /*
    |--------------------------------------------------------------------------
    | Database Transactions
    |--------------------------------------------------------------------------
    |
    | This configuration option determines if your data will only be synced
    | with your search indexes after a database transaction has committed.
    | This can help prevent race conditions during database operations.
    |
    */

    'after_commit' => false,

    /*
    |--------------------------------------------------------------------------
    | Chunk Sizes
    |--------------------------------------------------------------------------
    |
    | These options allow you to control the chunk size when importing large
    | amounts of data into your search indexes. This can help with performance
    | and memory usage when syncing large amounts of models.
    |
    */

    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Soft Deletes
    |--------------------------------------------------------------------------
    |
    | This option allows to control whether to keep the soft deleted records
    | in the search indexes. This is useful if you want to search for the
    | soft deleted records as well, otherwise they will be removed from the
    | search indexes.
    |
    */

    'soft_delete' => false,

    /*
    |--------------------------------------------------------------------------
    | Identify User
    |--------------------------------------------------------------------------
    |
    | This option allows you to control whether to add the user id as a meta
    | data to the search records. This is useful if you want to track the user
    | who created or updated the record.
    |
    */

    'identify' => env('SCOUT_IDENTIFY', false),

    /*
    |--------------------------------------------------------------------------
    | Algolia Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may place your Algolia configuration. The default driver for
    | Scout is Algolia, but you are free to add additional configuration
    | for other search engines as needed.
    |
    */

    'algolia' => [
        'id' => env('ALGOLIA_APP_ID', ''),
        'secret' => env('ALGOLIA_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Typesense Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may place your Typesense configuration. Scout will use this
    | configuration to interact with Typesense search engine.
    |
    */

    'typesense' => [
        'client-settings' => [
            'api_key' => env('TYPESENSE_API_KEY', 'xyz'),
            'nodes' => [
                [
                    'host' => env('TYPESENSE_HOST', 'localhost'),
                    'port' => env('TYPESENSE_PORT', '8108'),
                    'protocol' => 'http',
                ],
            ],
            'connection_timeout_seconds' => 2,
            'healthcheck_interval_seconds' => 30,
            'num_retries' => 3,
            'retry_interval_seconds' => 1,
        ],
        'settings' => [
            'index_by' => 'id',
            'collection_by' => 'name',
        ],
    ],
];
