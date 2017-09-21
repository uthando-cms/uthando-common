<?php

return [
    'uthando_common' => [
        'ssl' => false,
        'captcha' => [
            'class' => 'dumb'
        ],
        'akismet' => [
            'api_key' => '',
            'blog'    => '',
        ],
    ],
    'controllers' => [
        'invokables' => [
            'UthandoCommon\Controller\Captcha'              => 'UthandoCommon\Controller\CaptchaController',
            UthandoCommon\Mvc\Controller\Settings::class    => UthandoCommon\Mvc\Controller\Settings::class,
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
            'UthandoCommonCaptcha'                              => 'UthandoCommon\Form\Element\Captcha',
            'UthandoCommonLibPhoneNumberCountryList'            => 'UthandoCommon\Form\Element\LibPhoneNumberCountryList',

            UthandoCommon\Form\Settings\CommonSettings::class   => UthandoCommon\Form\Settings\CommonSettings::class,
            UthandoCommon\Form\Settings\AkismetFieldSet::class  => UthandoCommon\Form\Settings\AkismetFieldSet::class
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

            UthandoCommon\Options\AkismetOptions::class => UthandoCommon\Service\Factory\AkismetOptionsFactory::class,
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

            'formselect'            => 'UthandoCommon\Form\View\Helper\FormSelect',
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
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