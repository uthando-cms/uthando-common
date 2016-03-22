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

require_once(__DIR__ . '/src/UthandoCommon/Config/ConfigInterface.php');
require_once(__DIR__ . '/src/UthandoCommon/Config/ConfigTrait.php');

use UthandoCommon\Config\ConfigInterface;
use UthandoCommon\Config\ConfigTrait;
use UthandoCommon\Event\ConfigListener;
use UthandoCommon\Event\MvcListener;
use UthandoCommon\Event\TidyResponseSender;
use UthandoCommon\Event\ServiceListener;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ResponseSender\SendResponseEvent;

/**
 * Class Module
 *
 * @package UthandoCommon
 */
class Module implements ConsoleBannerProviderInterface, ConfigInterface
{
    use ConfigTrait;

    /**
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        /* @var $sm \Zend\ServiceManager\ServiceManager */
        $sm = $moduleManager->getEvent()->getParam('ServiceManager');
        $serviceListener = $sm->get('ServiceListener');
        $events = $moduleManager->getEventManager();

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

        $events->attach(new ConfigListener());
    }

    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $eventManager = $app->getEventManager();

        $eventManager->attach(new MvcListener());
        $eventManager->attach(new ServiceListener());

        $config = $app->getServiceManager()
            ->get('config');

        $tidyConfig = (isset($config['tidy'])) ? $config['tidy'] : ['enable' => false];

        if ($tidyConfig['enable']) {
            $eventManager->getSharedManager()->attach(
                'Zend\Mvc\SendResponseListener',
                SendResponseEvent::EVENT_SEND_RESPONSE,
                new TidyResponseSender($tidyConfig['config'], $event->getRequest()->isXmlHttpRequest())
            );
        }
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php'
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * @param Console $console
     * @return string
     */
    public function getConsoleBanner(Console $console)
    {
        return
            "==-------------------------------------------------------==\n" .
            "        Welcome to Uthando CMS Console-enabled app         \n" .
            "==-------------------------------------------------------==\n" .
            "Version 1.0\n";
    }
}
