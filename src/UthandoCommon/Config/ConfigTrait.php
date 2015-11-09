<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Config
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace UthandoCommon\Config;

use ReflectionClass;

/**
 * Class ConfigTrait
 *
 * @package UthandoCommon\Config
 */
trait ConfigTrait
{
    /**
     * @return array
     */
    public function getUthandoConfig() : array
    {
        $modulePath = $this->getModulePath();
        $routes     = include $modulePath . '/config/uthando-routes.config.php';
        $acl        = include $modulePath . '/config/uthando-user.config.php';
        $navigation = include $modulePath . '/config/uthando-navigation.config.php';
        $config     = array_merge($routes, $navigation, $acl);

        return $config;
    }

    /**
     * @return string
     */
    public function getModulePath() : string
    {
        $reflector = new ReflectionClass(get_class($this));
        $fn = $reflector->getFileName();
        return dirname($fn);
    }
}
