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
        if ($instance instanceof CacheStorageAwareInterface) {

            /* @var $cache \Zend\Cache\Storage\Adapter\AbstractAdapter */
            $cache = $serviceLocator->get('Zend\Cache\Service\StorageCacheFactory');

            $instance->setCache($cache);
        }
    }
}
