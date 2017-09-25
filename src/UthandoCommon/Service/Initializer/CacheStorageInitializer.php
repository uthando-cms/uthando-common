<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Service\Initializer
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Service\Initializer;

use UthandoCommon\Options\CacheOptions;
use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UthandoCommon\Cache\CacheStorageAwareInterface;

/**
 * Class CacheStorageInitializer
 *
 * @package UthandoCommon\Service\Initializer
 */
class CacheStorageInitializer implements InitializerInterface
{
    /**
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof CacheStorageAwareInterface ) {

            $cacheOptions = $serviceLocator->get(CacheOptions::class);

            if ($cacheOptions instanceof CacheOptions && $cacheOptions->isEnabled()) {

                $adapter = $cacheOptions->getAdapter();
                $cache = new $adapter;

                $cache->setOptions($cacheOptions->getOptions()->toArray());

                foreach ($cacheOptions->getPlugins() as $plugin) {
                    $pluginClass = new $plugin;
                    $cache->addPlugin($pluginClass);
                }
                $instance->setCache($cache);
            }
        }
    }
}
