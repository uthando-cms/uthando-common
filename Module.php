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
    	return [
        	'initializers' => [
            	'UthandoCommon\Service\DbAdapterInitializer' => 'UthandoCommon\Service\Initializer\DbAdapterInitializer',
            ]
        ];
    }
    
    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
        	    'FormatDate'       => 'UthandoCommon\View\FormatDate',
        	    'Request'          => 'UthandoCommon\View\Request',
        	    'tbAlert'          => 'UthandoCommon\View\Alert',
        	    'tbFlashMessenger' => 'UthandoCommon\View\FlashMessenger',
            ],
        ];
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
