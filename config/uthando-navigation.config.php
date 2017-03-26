<?php
return [
    'navigation' => [
        'admin' => [
            'admin' => [
                'pages' => [
                    'settings' => [
                        'pages' => [
                            'common-settings' => [
                                'label' => 'Common',
                                'action' => 'index',
                                'route' => 'admin/common',
                                'resource' => 'menu:admin',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
