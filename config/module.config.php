<?php

use UthandoCommon\Db\Adapter\AdapterServiceFactory as DbAdapterServiceFactory;
use UthandoCommon\Db\Table\AbstractTableFactory;
use UthandoCommon\Filter\HtmlPurifierFilter;
use UthandoCommon\Filter\Service\HtmlPurifierFactory;
use UthandoCommon\Form\Element\CacheAdapterSelect;
use UthandoCommon\Form\Element\CachePluginsSelect;
use UthandoCommon\Form\Settings\AkismetFieldSet;
use UthandoCommon\Form\Settings\Cache\FileSystemFieldSet;
use UthandoCommon\Form\Settings\CacheFieldSet;
use UthandoCommon\Form\Settings\CommonSettings;
use UthandoCommon\Form\Settings\GeneralFieldSet;
use UthandoCommon\Form\View\Helper\FormSelect;
use UthandoCommon\I18n\View\Helper\LibPhoneNumber;
use UthandoCommon\Mvc\Controller\Settings;
use UthandoCommon\Options\AkismetOptions;
use UthandoCommon\Options\CacheOptions;
use UthandoCommon\Options\DbOptions;
use UthandoCommon\Options\GeneralOptions;
use UthandoCommon\Service\Factory\AkismetOptionsFactory;
use UthandoCommon\Service\Factory\CacheOptionsFactory;
use UthandoCommon\Service\Factory\DbOptionsFactory;
use UthandoCommon\Service\Factory\GeneralOptionsFactory;
use UthandoCommon\View\Alert;
use UthandoCommon\View\ConvertToJsDateFormat;
use UthandoCommon\View\Enabled;
use UthandoCommon\View\FlashMessenger;
use UthandoCommon\View\FormatDate;
use UthandoCommon\View\FormManager;
use UthandoCommon\View\OptionsHelper;
use UthandoCommon\View\Request;
use Zend\Db\Adapter\Adapter as DbAdapter;

return [
    'uthando_common' => [
        'ssl' => false,
        'captcha' => [
            'class' => 'dumb'
        ],
    ],
    'controllers' => [
        'invokables' => [
            'UthandoCommon\Controller\Captcha'  => 'UthandoCommon\Controller\CaptchaController',
            Settings::class                     => Settings::class,
        ],
    ],
    'filters' => [
        'invokables' => [
            'UthandoCommonPhoneNumber'  => 'UthandoCommon\I18n\Filter\PhoneNumber',
            'UthandoSlug'               => 'UthandoCommon\Filter\Slug',
            'UthandoUcFirst'            => 'UthandoCommon\Filter\UcFirst',
            'UthandoUcwords'            => 'UthandoCommon\Filter\Ucwords',


        ],
        'factories' => [
            HtmlPurifierFilter::class => HtmlPurifierFactory::class,
        ]
    ],
    'form_elements' => [
        'invokables' => [
            'UthandoCommonCaptcha'                              => 'UthandoCommon\Form\Element\Captcha',
            'UthandoCommonLibPhoneNumberCountryList'            => 'UthandoCommon\Form\Element\LibPhoneNumberCountryList',

            CacheAdapterSelect::class   => CacheAdapterSelect::class,
            CachePluginsSelect::class   => CachePluginsSelect::class,

            CommonSettings::class       => CommonSettings::class,

            AkismetFieldSet::class      => AkismetFieldSet::class,
            CacheFieldSet::class        => CacheFieldSet::class,
            FileSystemFieldSet::class   => FileSystemFieldSet::class,
            GeneralFieldSet::class      => GeneralFieldSet::class,
        ],
    ],
    'service_manager' => [
        'aliases' => [
        ],
        'abstract_factories' => [
            AbstractTableFactory::class,
        ],
        'factories' => [
            'UthandoMapperManager'                      => 'UthandoCommon\Mapper\MapperManagerFactory',
            'UthandoModelManager'                       => 'UthandoCommon\Model\ModelManagerFactory',
            'UthandoServiceManager'                     => 'UthandoCommon\Service\ServiceManagerFactory',
            DbAdapter::class                            => DbAdapterServiceFactory::class,
            'Zend\Cache\Service\StorageCacheFactory'    => 'Zend\Cache\Service\StorageCacheFactory',

            AkismetOptions::class   => AkismetOptionsFactory::class,
            CacheOptions::class     => CacheOptionsFactory::class,
            DbOptions::class        => DbOptionsFactory::class,
            GeneralOptions::class   => GeneralOptionsFactory::class
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
        'aliases' => [
            'convertToJsDateFormat' => ConvertToJsDateFormat::class,
            'enabled'               => Enabled::class,
            'formatDate'            => FormatDate::class,
            'formManager'           => FormManager::class,
            'formSelect'            => FormSelect::class,
            'libPhoneNumber'        => LibPhoneNumber::class,
            'optionsHelper'         => OptionsHelper::class,
            'request'               => Request::class,
            'tbAlert'               => Alert::class,
            'tbFlashMessenger'      => FlashMessenger::class,
        ],
        'invokables' => [
            Alert::class                    => Alert::class,
            ConvertToJsDateFormat::class    => ConvertToJsDateFormat::class,
            Enabled::class                  => Enabled::class,
            FlashMessenger::class           => FlashMessenger::class,
            FormatDate::class               => FormatDate::class,
            FormManager::class              => FormManager::class,
            FormSelect::class               => FormSelect::class,
            LibPhoneNumber::class           => LibPhoneNumber::class,
            OptionsHelper::class            => OptionsHelper::class,
            Request::class                  => Request::class,
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