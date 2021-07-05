<?php

return [
    'middleware' => [
        'web' => ["web"],
        'api' => ["api"],
    ],

    'filesystem' => [
        'disk' => 'public',
        'path' => [
            'properties' => 'properties'
        ]
    ]

];
