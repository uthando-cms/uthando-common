<?php

return [
    'uthando_user' => [
        'acl' => [
            'roles' => [
                'guest' => [
                    'privileges' => [
                        'allow' => [
                            'controllers' => [
                                \UthandoCommon\Controller\CaptchaController::class => ['action' => 'all'],
                            ],
                        ],
                    ],
                ],
                'admin' => [
                    'privileges' => [
                        'allow' => [
                            'controllers' => [
                                \UthandoCommon\Mvc\Controller\Settings::class => ['action' => 'all'],
                            ]
                        ]
                    ]
                ]
            ],
            'resources' => [
                \UthandoCommon\Controller\CaptchaController::class,
                \UthandoCommon\Mvc\Controller\Settings::class
            ],
        ],
    ],
];
