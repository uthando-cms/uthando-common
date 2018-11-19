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

// need as autoloader not loaded at this point
require_once(__DIR__ . '/src/UthandoCommon/Config/ConfigInterface.php');
require_once(__DIR__ . '/src/UthandoCommon/Config/ConfigTrait.php');

use UthandoCommon\Config\ConfigInterface;
use UthandoCommon\Config\ConfigTrait;
use UthandoCommon\Event\ConfigListener;
use UthandoCommon\Event\MvcListener;
use UthandoCommon\Event\TidyResponseSender;
use UthandoCommon\Event\ServiceListener;
use UthandoCommon\Mapper\MapperInterface;
use UthandoCommon\Mapper\MapperManager;
use UthandoCommon\Model\ModelInterface;
use UthandoCommon\Model\ModelManager;
use UthandoCommon\Service\ServiceInterface;
use UthandoCommon\Service\ServiceManager;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Http\Request;
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
            MapperManager::class,
            'uthando_mappers',
            MapperInterface::class,
            'getUthandoMapperConfig'
        );

        $serviceListener->addServiceManager(
            ModelManager::class,
            'uthando_models',
            ModelInterface::class,
            'getUthandoModelConfig'
        );

        $serviceListener->addServiceManager(
            ServiceManager::class,
            'uthando_services',
            ServiceInterface::class,
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

        if ($event->getRequest() instanceof Request) {
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
