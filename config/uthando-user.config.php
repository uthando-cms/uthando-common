<?php

return [
    'uthando_user' => [
        'acl' => [
            'roles' => [
                'guest' => [
                    'privileges' => [
                        'allow' => [
                            'controllers' => [
                                'UthandoCommon\Controller\Captcha' => ['action' => 'all'],
                                UthandoCommon\Mvc\Controller\Settings::class => ['action' => 'all'],
                            ],
                        ],
                    ],
                ],
            ],
            'resources' => [
                'UthandoCommon\Controller\Captcha',
                UthandoCommon\Mvc\Controller\Settings::class
            ],
        ],
    ],
];
