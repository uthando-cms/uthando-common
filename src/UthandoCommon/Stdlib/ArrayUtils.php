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
 * Class ArrayUtils
 * @package UthandoCommon\Stdlib
 */
abstract class ArrayUtils
{
    /**
     * Traverse an array unsetting keys
     *
     * @param array $array array to traverse
     * @param array $keys keys to remove
     * @return array
     */
    public static function removeKeysFromMultiArray(&$array, $keys)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                self::traverseArray($value, $keys);
            } else {
                if (in_array($key, $keys) || '' == $value){
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }

    /**
     * transform a list into a multidimensional array
     * using a depth value
     *
     * @param array $array
     * @return array
     */
    public static function listToMultiArray($array)
    {
        $nested = [];
        $depths = [];

        foreach($array as $key => $arr) {

            if( $arr['depth'] == 0 ) {
                $nested[$key] = $arr;
            } else {
                $parent =& $nested;

                for ($i = 1; $i <= ($arr['depth']); $i++) {
                    $parent =& $parent[$depths[$i]];
                }

                $parent['pages'][$key] = $arr;
            }

            $depths[$arr['depth'] + 1] = $key;
        }

        return $nested;
    }
} 