<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Service\Factory
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2016 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Service\Factory;

use UthandoCommon\Options\DbOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DbOPtionsFactory
 *
 * @package UthandoCommon\Service\Factory
 */
class DbOptionsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $dbOptions = (isset($config['uthando_common']['db_options'])) ? $config['uthando_common']['db_options'] : [];

        return new DbOptions($dbOptions);
    }
}
