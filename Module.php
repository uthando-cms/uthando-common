<?php

namespace UthandoCommon;

use Exception;
use UthandoCommon\Event\MvcListener;
use UthandoCommon\Event\ServiceListener;
use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $eventManager = $app->getEventManager();
        
        $eventManager->attachAggregate(new MvcListener());
        $eventManager->attachAggregate(new ServiceListener());
    
    }

    public function getConfig()
    {
        return [
            'uthando-common' => [
                'ssl' => false
            ],
            'view_manager' => [
                'template_map' => include __DIR__ . '/template_map.php'
            ]
        ];
    }

    public function getServiceConfig()
    {
        return [
            'initializers' => [
                'UthandoCommon\Service\CacheStorageInitializer' => 'UthandoCommon\Service\Initializer\CacheStorageInitializer',
                'UthandoCommon\Service\DbAdapterInitializer' => 'UthandoCommon\Service\Initializer\DbAdapterInitializer'
            ]
        ];
    }

    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                'FormatDate' => 'UthandoCommon\View\FormatDate',
                'Request' => 'UthandoCommon\View\Request',
                'tbAlert' => 'UthandoCommon\View\Alert',
                'tbFlashMessenger' => 'UthandoCommon\View\FlashMessenger'
            ]
        ];
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php'
            ],
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        ];
    }
}
