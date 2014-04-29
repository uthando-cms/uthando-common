<?php

namespace UthandoCommon;

use Exception;
use UthandoCommon\Event\MvcListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        $app                 = $event->getApplication();
        $eventManager        = $app->getEventManager();
        
        $eventManager->attach(new MvcListener());
        
    }
    
    public function getConfig()
    {
        return [
            'uthando-common' => [
                'ssl' => false
            ],
            'view_manager' => [
                'template_map' => include __DIR__  .'/template_map.php',
            ],
        ];
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
