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

/**
 * Interface UthandoModuleConfigInterface
 *
 * @package UthandoCommon\Config
 */
interface ConfigInterface
{
    /**
     * @return array
     */
    public function getUthandoConfig() : array;

    /**
     * @return string
     */
    public function getModulePath() : string;
}
