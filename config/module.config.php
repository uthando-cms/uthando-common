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
            'UthandoCommonCaptcha'                      => 'UthandoCommon\Form\Element\Captcha',
            'UthandoCommonLibPhoneNumberCountryList'    => 'UthandoCommon\Form\Element\LibPhoneNumberCountryList',
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'UthandoCommon\Db\Table\AbstractTableFactory',
        ],
        'factories' => [
            'UthandoMapperManager'                      => 'UthandoCommon\Mapper\MapperManagerFactory',
            'UthandoModelManager'                       => 'UthandoCommon\Model\ModelManagerFactory',
            'UthandoServiceManager'                     => 'UthandoCommon\Service\ServiceManagerFactory',
            Zend\Db\Adapter\Adapter::class              => UthandoCommon\Db\Adapter\AdapterServiceFactory::class,
            'Zend\Cache\Service\StorageCacheFactory'    => 'Zend\Cache\Service\StorageCacheFactory',

            UthandoCommon\Options\DbOptions::class      => UthandoCommon\Service\Factory\DbOptionsFactory::class,
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
            'UthandoCommonAkismet'      => 'UthandoCommon\Validator\Akismet',
            'UthandoCommonPhoneNumber'  => 'UthandoCommon\I18n\Validator\PhoneNumber',
            'UthandoCommonPostCode'     => 'UthandoCommon\I18n\Validator\PostCode',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'Enabled'               => 'UthandoCommon\View\Enabled',
            'FormatDate'            => 'UthandoCommon\View\FormatDate',
            'FormManager'           => 'UthandoCommon\View\FormManager',
            'LibPhoneNumber'        => 'UthandoCommon\I18n\View\Helper\LibPhoneNumber',
            'Request'               => 'UthandoCommon\View\Request',
            'tbAlert'               => 'UthandoCommon\View\Alert',
            'tbFlashMessenger'      => 'UthandoCommon\View\FlashMessenger',
            'convertToJsDateFormat' => 'UthandoCommon\View\ConvertToJsDateFormat',
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'display_not_found_reason'  => true,
        'display_exceptions'        => true,
        'doctype'                   => 'HTML5',
        'not_found_template'        => 'error/404',
        'exception_template'        => 'error/index',
        'template_map'              => include __DIR__ . '/../template_map.php'
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