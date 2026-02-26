<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    */

    'default' => env('CACHE_DRIVER', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to get unique cache lifecycles.
    |
    | Supported drivers: "apc", "array", "database", "file",
    |            "memcached", "redis", "dynamodb", "octane", "null"
    |
    */

    'stores' => [

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
            'prefix' => '',
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        // Dedicated cache for products with optimized settings
        'products' => [
            'driver' => env('CACHE_STORES_PRODUCTS_DRIVER', 'redis'),
            'connection' => 'cache',
            'prefix' => 'products_cache',
        ],

        // Dedicated cache for images with optimized settings
        'images' => [
            'driver' => env('CACHE_STORES_IMAGES_DRIVER', 'redis'),
            'connection' => 'cache',
            'prefix' => 'images_cache',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing the APC, database, memcached, or Redis cache drivers we
    | might need a prefix to avoid collisions with other applications or
    | data in the same cache system. By default we'll use the Laravel_
    | prefix for each cache entry.
    |
    */

    'prefix' => env('CACHE_PREFIX', 'laravel'),

];
