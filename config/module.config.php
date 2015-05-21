<?php

return [
    'uthando_common' => [
        'ssl' => false,
        'captcha' => [
            'class' => 'dumb'
        ],
    ],
    'cache' => [
        'adapter' => [
            'name' => 'filesystem',
            'options' => [
                'ttl'                   => 60*60, // one hour
                'dirLevel'              => 0,
                //'cacheDir'              => './data/cache/db',
                'dirPermission'         => '700',
                'filePermission'        => '600',
            ],
        ],
        'plugins' => ['Serializer'],
    ],
    'uthando_user' => [
        'acl' => [
            'roles' => [
                'guest' => [
                    'privileges' => [
                        'allow' => [
                            'controllers' => [
                                'UthandoCommon\Controller\Captcha' => ['action' => 'all'],
                            ],
                        ],
                    ],
                ],
            ],
            'resources' => [
                'UthandoCommon\Controller\Captcha',
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'UthandoCommon\Controller\Captcha' => 'UthandoCommon\Controller\CaptchaController',
        ],
    ],
    'filters' => [
        'invokables' => [
            'UthandoCommonPhoneNumber'  => 'UthandoCommon\I18n\Filter\PhoneNumber',
            'UthandoSlug'               => 'UthandoCommon\Filter\Slug',
            'UthandoUcFirst'            => 'UthandoCommon\Filter\UcFirst',
            'UthandoUcwords'            => 'UthandoCommon\Filter\Ucwords',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'UthandoCommonCaptcha' => 'UthandoCommon\Form\Element\Captcha',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'UthandoMapperManager'                      => 'UthandoCommon\Mapper\MapperManagerFactory',
            'UthandoModelManager'                       => 'UthandoCommon\Model\ModelManagerFactory',
            'UthandoServiceManager'                     => 'UthandoCommon\Service\ServiceManagerFactory',
            'Zend\Db\Adapter\Adapter'                   => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Zend\Cache\Service\StorageCacheFactory'    => 'Zend\Cache\Service\StorageCacheFactory',
        ],
        'initializers' => [
            'UthandoCommon\Service\CacheStorageInitializer' => 'UthandoCommon\Service\Initializer\CacheStorageInitializer'
        ],
    ],
    'uthando_services' => [
        'initializers' => [
            'UthandoCommon\Service\CacheStorageInitializer' => 'UthandoCommon\Service\Initializer\CacheStorageInitializer'
        ],
    ],
    'validators' => [
        'invokables' => [
            'UthandoCommonPhoneNumber'  => 'UthandoCommon\I18n\Validator\PhoneNumber',
            'UthandoCommonPostCode'     => 'UthandoCommon\I18n\Validator\PostCode',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'FormatDate'        => 'UthandoCommon\View\FormatDate',
            'FormManager'       => 'UthandoCommon\View\FormManager',
            'Request'           => 'UthandoCommon\View\Request',
            'tbAlert'           => 'UthandoCommon\View\Alert',
            'tbFlashMessenger'  => 'UthandoCommon\View\FlashMessenger'
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_map' => include __DIR__ . '/../template_map.php'
    ],
    'router' => [
        'routes' => [
            'captcha-form-generate' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/captcha/[:id]',
                    'defaults' => [
                        '__NAMESPACE__' => 'UthandoCommon\Controller',
                        'controller'    => 'Captcha',
                        'action'        => 'generate'
                    ],
                ],
            ],
        ],
    ],
];