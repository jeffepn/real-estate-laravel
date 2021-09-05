<?php

return [
    'middleware' => [
        'web' => ["web"],
        'api' => ["api"],
    ],

    'filesystem' => [
        'disk' => 'public',
        'path' => [
            'properties' => 'properties',
        ],
    ],
    'optmize_images' => env('RE_OPTIMIZE_IMAGE', true),
    'url_home' => null
];
