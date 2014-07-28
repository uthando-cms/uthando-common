<?php
namespace UthandoCommon\Service\Initializer;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UthandoCommon\Cache\CacheStorageAwareInterface;

class CacheStorageInitializer implements InitializerInterface
{
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
    	if ($instance instanceof CacheStorageAwareInterface) {
    		
    		$cache = $serviceLocator->get('Zend\Cache\Service\StorageCacheFactory');
    			
    		$instance->setCache($cache);
    	}
    }
}
