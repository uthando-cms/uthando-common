<?php

return [
    'router' => [
        'routes' => [
            'admin' => [
        		'child_routes' => [
        			'common' => [
        				'type'    => 'Segment',
        				'options' => [
        					'route'    => '/common',
        					'defaults' => [
        						'__NAMESPACE__' => 'UthandoCommon\Mvc\Controller',
        						'controller'    => 'Settings',
        						'action'        => 'index',
        					],
        				],
        				'may_terminate' => true,
        			],
        		],
        	],
        ],
    ],
];
