<?php

use UthandoCommon\Controller\CaptchaController;
use UthandoCommon\Db\Adapter\AdapterServiceFactory as DbAdapterServiceFactory;
use UthandoCommon\Db\Table\AbstractTableFactory;
use UthandoCommon\Filter\HtmlPurifierFilter;
use UthandoCommon\Filter\Service\HtmlPurifierFactory;
use UthandoCommon\Filter\Slug;
use UthandoCommon\Filter\UcFirst;
use UthandoCommon\Filter\Ucwords;
use UthandoCommon\Form\Element\CacheAdapterSelect;
use UthandoCommon\Form\Element\CachePluginsSelect;
use UthandoCommon\Form\Element\Captcha;
use UthandoCommon\Form\Element\LibPhoneNumberCountryList;
use UthandoCommon\Form\Settings\AkismetFieldSet;
use UthandoCommon\Form\Settings\Cache\FileSystemFieldSet;
use UthandoCommon\Form\Settings\CacheFieldSet;
use UthandoCommon\Form\Settings\CommonSettings;
use UthandoCommon\Form\Settings\GeneralFieldSet;
use UthandoCommon\Form\View\Helper\FormSelect;
use UthandoCommon\I18n\Filter\PhoneNumber;
use UthandoCommon\I18n\Validator\PhoneNumber as PhoneNumberValidator;
use UthandoCommon\I18n\Validator\PostCode;
use UthandoCommon\I18n\View\Helper\LibPhoneNumber;
use UthandoCommon\Mapper\MapperManager;
use UthandoCommon\Mapper\MapperManagerFactory;
use UthandoCommon\Model\ModelManager;
use UthandoCommon\Model\ModelManagerFactory;
use UthandoCommon\Mvc\Controller\Settings;
use UthandoCommon\Options\AkismetOptions;
use UthandoCommon\Options\CacheOptions;
use UthandoCommon\Options\DbOptions;
use UthandoCommon\Options\GeneralOptions;
use UthandoCommon\Service\Factory\AkismetOptionsFactory;
use UthandoCommon\Service\Factory\CacheOptionsFactory;
use UthandoCommon\Service\Factory\DbOptionsFactory;
use UthandoCommon\Service\Factory\GeneralOptionsFactory;
use UthandoCommon\Service\Initializer\CacheStorageInitializer;
use UthandoCommon\Service\ServiceManager;
use UthandoCommon\Service\ServiceManagerFactory;
use UthandoCommon\View\Alert;
use UthandoCommon\View\ConvertToJsDateFormat;
use UthandoCommon\View\Enabled;
use UthandoCommon\View\FlashMessenger;
use UthandoCommon\View\FormatDate;
use UthandoCommon\View\FormManager;
use UthandoCommon\View\OptionsHelper;
use UthandoCommon\View\Request;
use Zend\Cache\Service\StorageCacheFactory;
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
            CaptchaController::class    => CaptchaController::class,
            Settings::class             => Settings::class,
        ],
    ],
    'filters' => [
        'aliases' => [
            'UthandoCommonPhoneNumber'  => PhoneNumber::class,
            'UthandoSlug'               => Slug::class,
            'UthandoUcFirst'            => UcFirst::class,
            'UthandoUcwords'            => Ucwords::class,
        ],
        'invokables' => [
            PhoneNumber::class  => PhoneNumber::class,
            Slug::class         => Slug::class,
            UcFirst::class      => UcFirst::class,
            Ucwords::class      => Ucwords::class,
        ],
        'factories' => [
            HtmlPurifierFilter::class => HtmlPurifierFactory::class,
        ]
    ],
    'form_elements' => [
        'aliases' => [
            'UthandoCommonCaptcha'                      => Captcha::class,
            'UthandoCommonLibPhoneNumberCountryList'    => LibPhoneNumberCountryList::class,
        ],
        'invokables' => [
            Captcha::class                      => Captcha::class,
            LibPhoneNumberCountryList::class    => LibPhoneNumberCountryList::class,

            CacheAdapterSelect::class           => CacheAdapterSelect::class,
            CachePluginsSelect::class           => CachePluginsSelect::class,

            CommonSettings::class               => CommonSettings::class,

            AkismetFieldSet::class              => AkismetFieldSet::class,
            CacheFieldSet::class                => CacheFieldSet::class,
            FileSystemFieldSet::class           => FileSystemFieldSet::class,
            GeneralFieldSet::class              => GeneralFieldSet::class,
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            AbstractTableFactory::class,
        ],
        'aliases' => [
            'UthandoMapperManager'  => MapperManager::class,
            'UthandoModelManager'   => ModelManager::class,
            'UthandoServiceManager' => ServiceManager::class,
        ],
        'factories' => [
            MapperManager::class        => MapperManagerFactory::class,
            ModelManager::class         => ModelManagerFactory::class,
            ServiceManager::class       => ServiceManagerFactory::class,
            DbAdapter::class            => DbAdapterServiceFactory::class,
            StorageCacheFactory::class  => StorageCacheFactory::class,

            AkismetOptions::class       => AkismetOptionsFactory::class,
            CacheOptions::class         => CacheOptionsFactory::class,
            DbOptions::class            => DbOptionsFactory::class,
            GeneralOptions::class       => GeneralOptionsFactory::class
        ],
    ],
    'uthando_services' => [
        'initializers' => [
            CacheStorageInitializer::class => CacheStorageInitializer::class,
        ],
    ],
    'validators' => [
        'aliases' => [
            'UthandoCommonPhoneNumber'  => PhoneNumberValidator::class,
            'UthandoCommonPostCode'     => PostCode::class,
        ],
        'invokables' => [
            PhoneNumberValidator::class => PhoneNumberValidator::class,
            PostCode::class             => PostCode::class,
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
                        'controller'    => CaptchaController::class,
                        'action'        => 'generate'
                    ],
                ],
            ],
        ],
    ],
];