<?php
namespace UthandoCommon\Service\Initializer;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UthandoCommon\Cache\CacheStorageAwareInterface;

class CacheStorageInitializer implements InitializerInterface
{
    /**
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
    	if ($instance instanceof CacheStorageAwareInterface) {

            /* @var $cache \Zend\Cache\Storage\Adapter\AbstractAdapter */
    		$cache = $serviceLocator->get('Zend\Cache\Service\StorageCacheFactory');
    			
    		$instance->setCache($cache);
    	}
    }
}
