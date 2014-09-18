<?php

namespace UthandoCommon;

use Exception;
use UthandoCommon\Event\MvcListener;
use UthandoCommon\Event\ServiceListener;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

class Module
{
    public function init(ModuleManager $moduleManager)
    {
        $sm = $moduleManager->getEvent()->getParam('ServiceManager');
        $serviceListener = $sm->get('ServiceListener');
        $serviceListener->addServiceManager(
            'UthandoMapperManager',
            'uthando_mappers',
            'UthandoCommon\Mapper\MapperInterface',
            'getUthandoMapperConfig'
        );

        $serviceListener->addServiceManager(
            'UthandoModelManager',
            'uthando_models',
            'UthandoCommon\Model\ModelInterface',
            'getUthandoModelConfig'
        );
    }

    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $eventManager = $app->getEventManager();
        
        $eventManager->attachAggregate(new MvcListener());
        $eventManager->attachAggregate(new ServiceListener());
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/config.php';
    }

    public function getFilterConfig()
    {
        return include __DIR__ . '/config/filter.config.php';
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }

    public function getViewHelperConfig()
    {
        return include __DIR__ . '/config/viewHelper.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php'
            ],
        ];
    }
}
