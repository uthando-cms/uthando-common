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

use Zend\Cache\Service\PluginManagerLookupTrait;
use Zend\Cache\StorageFactory;
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
    use PluginManagerLookupTrait;

    /**
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof CacheStorageAwareInterface ) {

            $config     = $serviceLocator->get('config');
            $options    = $config['uthando_common']['cache'] ?: [];

            if (!empty($options)) {
                $this->prepareStorageFactory($serviceLocator);
                $cache = StorageFactory::factory($options);
                $instance->setCache($cache);
            }
        }
    }
}
