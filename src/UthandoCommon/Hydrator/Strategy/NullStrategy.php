<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Hydrator\Strategy
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Hydrator\Strategy;

use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class NullStrategy
 *
 * @package UthandoCommon\Hydrator\Strategy
 */
class NullStrategy implements StrategyInterface
{
    public function extract($value)
    {
        return (0 == $value || '' == $value) ? null : $value;
    }

    public function hydrate($value)
    {
        return (0 == $value || '' == $value) ? null : $value;
    }
}
