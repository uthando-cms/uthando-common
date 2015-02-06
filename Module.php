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

use UthandoCommon\Event\MvcListener;
use UthandoCommon\Event\ServiceListener;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package UthandoCommon
 */
class Module implements ConsoleBannerProviderInterface
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

        $serviceListener->addServiceManager(
            'UthandoServiceManager',
            'uthando_services',
            'UthandoCommon\Service\ServiceInterface',
            'getUthandoServiceConfig'
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
    
    public function getControllerConfig()
    {
        return include __DIR__ . '/config/controller.config.php';
    }

    public function getFilterConfig()
    {
        return include __DIR__ . '/config/filter.config.php';
    }
    
    public function getFormElementConfig()
    {
        return include __DIR__ . '/config/formElement.config.php';
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }

    public function getViewHelperConfig()
    {
        return include __DIR__ . '/config/viewHelper.config.php';
    }

    public function getUthandoServiceConfig()
    {
        return include __DIR__ . '/config/uthandoServices.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php'
            ],
        ];
    }

    /**
     * @param Console $console
     * @return string
     */
    public function getConsoleBanner(Console $console){
        return
            "==-------------------------------------------------------==\n" .
            "        Welcome to Uthando CMS Console-enabled app         \n" .
            "==-------------------------------------------------------==\n" .
            "Version 1.0\n";
    }
}
