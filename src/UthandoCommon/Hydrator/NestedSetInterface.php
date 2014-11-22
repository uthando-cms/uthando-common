<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 * 
 * @package   UthandoCommon\Hydrator
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Interface NestedSetInterface
 * @package UthandoCommon\Hydrator
 */
interface NestedSetInterface extends HydratorInterface
{
    public function addDepth();
} 