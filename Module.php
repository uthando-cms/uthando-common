<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */
namespace UthandoCommon;

use Exception;
use UthandoCommon\Event\MvcListener;
use UthandoCommon\Event\ServiceListener;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package UthandoCommon
 */
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
