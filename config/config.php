<?php

return [
    'uthando_common' => [
        'ssl' => false,
    ],
    'cache' => [
        'adapter' => [
            'name' => 'filesystem',
            'options' => [
                'ttl'                   => 60*60, // one hour
                'dirLevel'              => 0,
                'cacheDir'              => './data/cache/db',
                'dirPermission'         => 0700,
                'filePermission'        => 0600,
            ],
        ],
        'plugins' => ['Serializer'],
    ],
    'view_manager' => [
        'template_map' => include __DIR__ . '/../template_map.php'
    ],
];