<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 2017 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Service\Factory;

use UthandoCommon\Options\AkismetOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AkismetOptionsFactory
 *
 * @package UthandoCommon\Service\Factory
 */
class AkismetOptionsFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator): AkismetOptions
    {
        $config = $serviceLocator->get('config');
        $options = $config['uthando_common']['akismet'] ?? [];

        return new AkismetOptions($options);
    }
}