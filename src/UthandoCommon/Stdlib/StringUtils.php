<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Stdlib
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Stdlib;

/**
 * Class StringUtils
 *
 * @package UthandoCommon\Stdlib
 */
abstract class StringUtils
{
    /**
     * Check to see if string starts with a string
     *
     * @param string $string
     * @param string $look
     * @return bool
     */
    public static function endsWith($string, $look)
    {
        return strrpos($string, $look) === strlen($string) - strlen($look);
    }

    /**
     * Checks to see if a string ends with a string.
     *
     * @param string $string
     * @param string $look
     * @return bool
     */
    public static function startsWith($string, $look)
    {
        return strpos($string, $look) === 0;
    }
}
