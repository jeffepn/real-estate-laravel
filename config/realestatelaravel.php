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
            // 'banners' => 'banners'
        ]
    ],
    'url_home' => null
];
